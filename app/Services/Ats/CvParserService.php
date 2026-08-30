<?php

namespace App\Services\Ats;

use Illuminate\Support\Str;

class CvParserService
{
    public function __construct(private CvTextExtractor $extractor) {}

    /**
     * @return array{
     *   text:string,
     *   emails:array<int,string>,
     *   phones:array<int,string>,
     *   skills:array<int,string>,
     *   years_experience:?int,
     *   education_level:?string,
     *   summary:?string,
     *   links:array<int,string>
     * }
     */
    public function parseFromDisk(string $disk, string $path): array
    {
        $text = $this->extractor->extractFromDisk($disk, $path);

        return $this->parseText($text);
    }

    public function parseText(string $text): array
    {
        $lower = Str::lower($text);

        return [
            'text' => $text,
            'emails' => $this->extractEmails($text),
            'phones' => $this->extractPhones($text),
            'skills' => $this->extractSkills($lower),
            'years_experience' => $this->extractYears($lower),
            'education_level' => $this->extractEducation($lower),
            'summary' => $this->extractSummary($text),
            'links' => $this->extractLinks($text),
        ];
    }

    /** @return array<int, string> */
    private function extractEmails(string $text): array
    {
        preg_match_all('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $text, $m);

        return array_values(array_unique(array_map('strtolower', $m[0] ?? [])));
    }

    /** @return array<int, string> */
    private function extractPhones(string $text): array
    {
        preg_match_all('/(?:\+?\d{1,3}[\s\-]?)?(?:\(?\d{2,4}\)?[\s\-]?)?\d{3,4}[\s\-]?\d{3,4}/', $text, $m);
        $phones = [];
        foreach ($m[0] ?? [] as $p) {
            $digits = preg_replace('/\D+/', '', $p) ?? '';
            if (strlen($digits) >= 8 && strlen($digits) <= 15) {
                $phones[] = trim($p);
            }
        }

        return array_values(array_unique($phones));
    }

    /** @return array<int, string> */
    private function extractSkills(string $lower): array
    {
        $found = [];
        foreach (config('ats.skills_dictionary', []) as $skill) {
            $skill = Str::lower((string) $skill);
            $pattern = '/(?<![a-z0-9])'.preg_quote($skill, '/').'(?![a-z0-9])/i';
            if (preg_match($pattern, $lower)) {
                $found[] = $skill;
            }
        }

        return array_values(array_unique($found));
    }

    private function extractYears(string $lower): ?int
    {
        $patterns = [
            '/(\d{1,2})\+?\s*(?:years?|yrs?)\s*(?:of\s*)?experience/i',
            '/experience\s*(?:of\s*)?(\d{1,2})\+?\s*(?:years?|yrs?)/i',
            '/خبرة\s*(?:لا تقل عن\s*)?(\d{1,2})\s*(?:سنوات|سنة|أعوام|عام)/u',
            '/(\d{1,2})\s*(?:سنوات|سنة|أعوام|عام)\s*(?:خبرة|من الخبرة)/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lower, $m)) {
                $years = (int) $m[1];
                if ($years > 0 && $years <= 40) {
                    return $years;
                }
            }
        }

        // Fallback: span of employment years in CV (e.g. 2018 - 2024)
        if (preg_match_all('/(20\d{2}|19\d{2})\s*[-–to]+\s*(20\d{2}|19\d{2}|present|now|الحالي|حتى الآن)/iu', $lower, $matches, PREG_SET_ORDER)) {
            $total = 0;
            foreach ($matches as $m) {
                $start = (int) $m[1];
                $endRaw = Str::lower($m[2]);
                $end = preg_match('/^\d{4}$/', $endRaw) ? (int) $endRaw : (int) date('Y');
                if ($end >= $start && ($end - $start) <= 40) {
                    $total += ($end - $start);
                }
            }
            if ($total > 0) {
                return min(40, $total);
            }
        }

        return null;
    }

    private function extractEducation(string $lower): ?string
    {
        foreach (config('ats.education_keywords', []) as $needle => $label) {
            if (Str::contains($lower, Str::lower((string) $needle))) {
                return $label;
            }
        }

        return null;
    }

    private function extractSummary(string $text): ?string
    {
        $clean = trim(preg_replace("/\s+/u", ' ', $text) ?? $text);
        if ($clean === '') {
            return null;
        }

        return Str::limit($clean, 400);
    }

    /** @return array<int, string> */
    private function extractLinks(string $text): array
    {
        preg_match_all('#https?://[^\s<>"\']+#i', $text, $m);
        $links = array_map(fn ($u) => rtrim($u, '.,);'), $m[0] ?? []);

        return array_values(array_unique($links));
    }
}
