<?php

namespace App\Services\Ats;

use App\Models\ApplicationAtsScore;
use App\Models\JobApplication;
use App\Models\JobAtsCriterion;
use App\Models\JobPosting;
use App\Services\CvStorageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AtsEvaluationService
{
    public function __construct(
        private CvParserService $parser,
        private CvStorageService $cvStorage,
    ) {}

    public function ensureCriteria(JobPosting $job): void
    {
        if ($job->atsCriteria()->exists()) {
            return;
        }

        foreach (config('ats.default_criteria', []) as $row) {
            $job->atsCriteria()->create([
                'code' => $row['code'],
                'label' => $row['label'],
                'description' => $row['description'] ?? null,
                'type' => $row['type'] ?? 'auto',
                'weight' => $row['weight'] ?? 10,
                'config' => $row['config'] ?? [],
                'is_active' => true,
                'sort_order' => $row['sort_order'] ?? 0,
            ]);
        }
    }

    public function process(JobApplication $application): JobApplication
    {
        $application->loadMissing('jobPosting');
        $job = $application->jobPosting;
        if (! $job) {
            return $application;
        }

        $this->ensureCriteria($job);

        if ($application->cv_path) {
            $this->parseCv($application);
            $application->refresh();
        }

        $this->evaluate($application->fresh(['jobPosting', 'atsScores']));

        return $application->fresh(['atsScores.criterion', 'jobPosting']);
    }

    public function parseCv(JobApplication $application): void
    {
        if (! $application->cv_path) {
            $application->update([
                'parse_status' => 'skipped',
                'parse_error' => null,
            ]);

            return;
        }

        try {
            $application->loadMissing('jobPosting');
            $extraSkills = $this->jobSkillHints($application->jobPosting);
            $binary = $this->cvStorage->get($application->cv_path);
            $parsed = $this->parser->parseFromContents(
                $binary,
                pathinfo($application->cv_path, PATHINFO_EXTENSION),
                $extraSkills
            );

            // Also mine the application form fields (message / links) for skills.
            $formBlob = trim(implode("\n", array_filter([
                $application->message,
                $application->linkedin_url,
                $application->portfolio_url,
                $application->full_name,
            ])));
            if ($formBlob !== '') {
                $fromForm = $this->parser->parseText($formBlob, $extraSkills);
                $parsed['skills'] = array_values(array_unique(array_merge($parsed['skills'], $fromForm['skills'])));
                $parsed['emails'] = array_values(array_unique(array_merge($parsed['emails'], $fromForm['emails'])));
                $parsed['phones'] = array_values(array_unique(array_merge($parsed['phones'], $fromForm['phones'])));
                $parsed['links'] = array_values(array_unique(array_merge($parsed['links'], $fromForm['links'])));
                if (! $parsed['years_experience'] && $fromForm['years_experience']) {
                    $parsed['years_experience'] = $fromForm['years_experience'];
                }
                if (! $parsed['education_level'] && $fromForm['education_level']) {
                    $parsed['education_level'] = $fromForm['education_level'];
                }
            }

            $text = trim((string) ($parsed['text'] ?? ''));
            $isScanned = $this->looksLikeScannedCv($text, strlen($binary));

            $application->update([
                'cv_text' => Str::limit($text, 50000, ''),
                'parsed_cv' => [
                    'emails' => $parsed['emails'],
                    'phones' => $parsed['phones'],
                    'summary' => $parsed['summary'],
                    'links' => $parsed['links'],
                    'scanned' => $isScanned,
                ],
                'extracted_skills' => $parsed['skills'],
                'years_experience' => $parsed['years_experience'],
                'education_level' => $parsed['education_level'],
                'parse_status' => $isScanned ? 'scanned' : (filled($text) ? 'parsed' : 'empty'),
                'parse_error' => $isScanned
                    ? 'السيرة ممسوحة ضوئياً (صورة/CamScanner) بدون نص قابل للاستخراج. التقييم يعتمد على بيانات النموذج + المراجعة اليدوية.'
                    : (filled($text) ? null : 'No extractable text found in CV'),
                'parsed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('ATS CV parse failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
            $application->update([
                'parse_status' => 'failed',
                'parse_error' => Str::limit($e->getMessage(), 1000),
                'parsed_at' => now(),
            ]);
        }
    }

    /** @return array<int, string> */
    private function jobSkillHints(?JobPosting $job): array
    {
        if (! $job) {
            return [];
        }

        $blob = Str::lower(trim(implode(' ', array_filter([
            $job->title,
            $job->summary,
            $job->description,
            $job->requirements,
        ]))));

        $hints = [];
        foreach (config('ats.skills_dictionary', []) as $skill) {
            $skill = Str::lower((string) $skill);
            if ($skill !== '' && Str::contains($blob, $skill)) {
                $hints[] = $skill;
            }
        }

        // Free-form tokens from requirements (3+ chars)
        preg_match_all('/[a-zA-Z\x{0600}-\x{06FF}]{3,}/u', $blob, $m);
        foreach ($m[0] ?? [] as $word) {
            $word = Str::lower($word);
            if (mb_strlen($word) >= 3) {
                $hints[] = $word;
            }
        }

        return array_values(array_unique(array_slice($hints, 0, 80)));
    }

    public function evaluate(JobApplication $application): void
    {
        $application->loadMissing(['jobPosting.atsCriteria', 'atsScores']);
        $job = $application->jobPosting;
        if (! $job) {
            return;
        }

        $criteria = $job->atsCriteria()->where('is_active', true)->orderBy('sort_order')->get();
        $weighted = 0;
        $weightSum = 0;

        foreach ($criteria as $criterion) {
            if ($criterion->type === 'manual') {
                $existing = $application->atsScores
                    ->firstWhere('job_ats_criterion_id', $criterion->id);

                $score = (int) ($existing?->score ?? 0);
                $weight = max(0, (int) $criterion->weight);
                if ($existing) {
                    $weighted += $score * $weight;
                    $weightSum += $weight;
                }

                continue;
            }

            [$score, $notes, $evidence] = $this->scoreAuto($application, $criterion);

            ApplicationAtsScore::updateOrCreate(
                [
                    'job_application_id' => $application->id,
                    'job_ats_criterion_id' => $criterion->id,
                ],
                [
                    'score' => $score,
                    'max_score' => 100,
                    'notes' => $notes,
                    'evidence' => $evidence,
                    'source' => 'system',
                    'scored_by' => null,
                ]
            );

            $weight = max(0, (int) $criterion->weight);
            $weighted += $score * $weight;
            $weightSum += $weight;
        }

        $total = $weightSum > 0 ? (int) round($weighted / $weightSum) : null;

        $application->update([
            'ats_score' => $total,
            'ats_grade' => $this->grade($total),
            'stage' => $application->stage ?: (config('ats.status_to_stage.'.$application->status) ?? 'applied'),
            'evaluated_at' => now(),
        ]);
    }

    /**
     * @return array{0:int,1:?string,2:array}
     */
    private function scoreAuto(JobApplication $application, JobAtsCriterion $criterion): array
    {
        $source = $criterion->config['source'] ?? $criterion->code;

        return match ($source) {
            'skills' => $this->scoreSkills($application),
            'years' => $this->scoreYears($application, $criterion),
            'requirements' => $this->scoreRequirements($application),
            'links' => $this->scoreLinks($application),
            'cover_letter' => $this->scoreCoverLetter($application),
            default => [0, 'Unknown auto criterion', []],
        };
    }

    /** @return array{0:int,1:?string,2:array} */
    private function scoreSkills(JobApplication $application): array
    {
        $cvSkills = collect($application->extracted_skills ?? [])->map(fn ($s) => Str::lower((string) $s))->all();
        $jobBlob = trim(implode(' ', array_filter([
            $application->jobPosting?->title,
            $application->jobPosting?->summary,
            $application->jobPosting?->description,
            $application->jobPosting?->requirements,
        ])));
        $required = $this->requirementTokens($jobBlob);
        $requiredSkills = array_values(array_intersect($required, array_map('strtolower', config('ats.skills_dictionary', []))));

        // Also treat dictionary skills present in job text as required.
        $jobLower = Str::lower($jobBlob);
        foreach (config('ats.skills_dictionary', []) as $skill) {
            $skill = Str::lower((string) $skill);
            if ($skill !== '' && Str::contains($jobLower, $skill)) {
                $requiredSkills[] = $skill;
            }
        }
        $requiredSkills = array_values(array_unique($requiredSkills));

        if ($requiredSkills === []) {
            $count = count($cvSkills);
            $score = min(100, $count * 12);

            return [$score, $count ? "Detected {$count} skills" : 'No skills detected', ['skills' => $cvSkills]];
        }

        $matched = array_values(array_intersect($requiredSkills, $cvSkills));
        // Fallback: skill may appear in raw CV text even if not in extracted list.
        $cvText = Str::lower((string) $application->cv_text);
        foreach ($requiredSkills as $skill) {
            if (! in_array($skill, $matched, true) && Str::contains($cvText, $skill)) {
                $matched[] = $skill;
            }
        }
        $matched = array_values(array_unique($matched));
        $score = (int) round((count($matched) / max(1, count($requiredSkills))) * 100);

        return [
            $score,
            count($matched).'/'.count($requiredSkills).' required skills matched',
            ['required' => $requiredSkills, 'matched' => $matched, 'cv_skills' => $cvSkills],
        ];
    }

    /** @return array{0:int,1:?string,2:array} */
    private function scoreYears(JobApplication $application, JobAtsCriterion $criterion): array
    {
        $years = $application->years_experience;
        $target = (int) ($criterion->config['target_years'] ?? 3);
        if ($years === null) {
            return [35, 'Years of experience not detected', ['years' => null, 'target' => $target]];
        }

        if ($years >= $target) {
            $score = min(100, 70 + (($years - $target) * 6));
        } else {
            $score = (int) max(20, round(($years / max(1, $target)) * 70));
        }

        return [$score, "Detected ~{$years} years (target {$target})", ['years' => $years, 'target' => $target]];
    }

    /** @return array{0:int,1:?string,2:array} */
    private function scoreRequirements(JobApplication $application): array
    {
        $haystack = Str::lower(($application->cv_text ?? '').' '.implode(' ', $application->extracted_skills ?? []));
        $tokens = $this->requirementTokens($application->jobPosting?->requirements ?? '');
        if ($tokens === []) {
            return [50, 'No requirement keywords configured', []];
        }

        $matched = [];
        foreach ($tokens as $token) {
            if (Str::contains($haystack, $token)) {
                $matched[] = $token;
            }
        }

        $score = (int) round((count($matched) / count($tokens)) * 100);

        return [$score, count($matched).'/'.count($tokens).' requirement keywords found', ['matched' => $matched, 'tokens' => $tokens]];
    }

    /** @return array{0:int,1:?string,2:array} */
    private function scoreLinks(JobApplication $application): array
    {
        $score = 0;
        $hits = [];
        if (filled($application->linkedin_url)) {
            $score += 40;
            $hits[] = 'linkedin_form';
        }
        if (filled($application->portfolio_url)) {
            $score += 40;
            $hits[] = 'portfolio_form';
        }

        $links = $application->parsed_cv['links'] ?? [];
        foreach ($links as $link) {
            $l = Str::lower((string) $link);
            if (str_contains($l, 'github.com') && ! in_array('github_cv', $hits, true)) {
                $score += 20;
                $hits[] = 'github_cv';
            }
            if (str_contains($l, 'linkedin.com') && ! in_array('linkedin_cv', $hits, true)) {
                $score += 10;
                $hits[] = 'linkedin_cv';
            }
        }

        return [min(100, $score), $hits ? 'Professional links found' : 'No professional links', ['hits' => $hits, 'links' => $links]];
    }

    /** @return array{0:int,1:?string,2:array} */
    private function scoreCoverLetter(JobApplication $application): array
    {
        $message = trim((string) $application->message);
        if ($message === '') {
            return [25, 'No cover message provided', ['length' => 0]];
        }

        $len = mb_strlen($message);
        $score = match (true) {
            $len >= 250 => 95,
            $len >= 120 => 80,
            $len >= 60 => 60,
            default => 40,
        };

        return [$score, "Cover letter length: {$len} chars", ['length' => $len]];
    }

    /** @return array<int, string> */
    private function requirementTokens(?string $requirements): array
    {
        $text = Str::lower((string) $requirements);
        if ($text === '') {
            return [];
        }

        $tokens = [];
        foreach (config('ats.skills_dictionary', []) as $skill) {
            $skill = Str::lower((string) $skill);
            if (Str::contains($text, $skill)) {
                $tokens[] = $skill;
            }
        }

        // Also include significant words (3+ chars) from requirements.
        preg_match_all('/[a-zA-Z\x{0600}-\x{06FF}]{3,}/u', $text, $m);
        foreach ($m[0] ?? [] as $word) {
            $word = Str::lower($word);
            if (in_array($word, ['and', 'the', 'for', 'with', 'من', 'على', 'في', 'أن', 'إلى'], true)) {
                continue;
            }
            if (strlen($word) >= 4) {
                $tokens[] = $word;
            }
        }

        return array_values(array_unique(array_slice($tokens, 0, 40)));
    }

    private function looksLikeScannedCv(string $text, int $bytes): bool
    {
        $clean = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
        if ($clean === '') {
            return $bytes > 5000; // large binary with no text ≈ scan/image PDF
        }

        if (Str::contains(Str::lower($clean), ['camscanner', 'cam scanner', 'scaned by', 'scanned by'])) {
            return true;
        }

        // Very little text relative to file size.
        return mb_strlen($clean) < 40 && $bytes > 20000;
    }

    private function grade(?int $score): ?string
    {
        if ($score === null) {
            return null;
        }

        return match (true) {
            $score >= 85 => 'A',
            $score >= 70 => 'B',
            $score >= 55 => 'C',
            default => 'D',
        };
    }
}
