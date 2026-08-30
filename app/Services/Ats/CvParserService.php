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
    public function parseFromDisk(string $disk, string $path, array $extraSkills = []): array
    {
        $text = $this->extractor->extractFromDisk($disk, $path);

        return $this->parseText($text, $extraSkills);
    }

    public function parseFromContents(string $binary, string $extension, array $extraSkills = []): array
    {
        $text = $this->extractor->extractFromContents($binary, $extension);
        $parsed = $this->parseText($text, $extraSkills);
        $parsed['ocr'] = $this->extractor->usedOcr;

        return $parsed;
    }

    public function parseText(string $text, array $extraSkills = []): array
    {
        $lower = Str::lower($text);

        return [
            'text' => $text,
            'emails' => $this->extractEmails($text),
            'phones' => $this->extractPhones($text),
            'skills' => $this->extractSkills($lower, $extraSkills),
            'years_experience' => $this->extractYears($lower),
            'education_level' => $this->extractEducation($text),
            'summary' => $this->extractSummary($text),
            'links' => $this->extractLinks($text),
            'universities' => $this->extractUniversities($text),
            'national_ids' => $this->extractNationalIds($text),
            'candidate_name' => $this->extractCandidateName($text),
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
        $phones = [];

        // Egyptian mobiles (11 digits) — common in CVs.
        if (preg_match_all('/(?:\+?20[\s\-]?)?0?1[0125][\s\-]?\d{3}[\s\-]?\d{4}/', $text, $m)) {
            foreach ($m[0] as $p) {
                $digits = preg_replace('/\D+/', '', $p) ?? '';
                if (str_starts_with($digits, '20')) {
                    $digits = '0'.substr($digits, 2);
                }
                if (strlen($digits) === 11 && str_starts_with($digits, '01')) {
                    $phones[] = $digits;
                }
            }
        }

        // International / general fallback (avoid 14-digit national IDs).
        if ($phones === [] && preg_match_all('/(?:\+?\d{1,3}[\s\-]?)?(?:\(?\d{2,4}\)?[\s\-]?)?\d{3,4}[\s\-]?\d{3,4}/', $text, $m)) {
            foreach ($m[0] as $p) {
                $digits = preg_replace('/\D+/', '', $p) ?? '';
                if (strlen($digits) >= 9 && strlen($digits) <= 13 && strlen($digits) !== 14) {
                    $phones[] = trim($p);
                }
            }
        }

        return array_values(array_unique($phones));
    }

    /** @return array<int, string> */
    private function extractSkills(string $lower, array $extraSkills = []): array
    {
        $dictionary = array_merge(config('ats.skills_dictionary', []), $extraSkills);
        $found = [];
        foreach ($dictionary as $skill) {
            $skill = Str::lower(trim((string) $skill));
            if ($skill === '' || mb_strlen($skill) < 2) {
                continue;
            }
            $pattern = '/(?<![a-z0-9])'.preg_quote($skill, '/').'(?![a-z0-9])/iu';
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

    private function extractEducation(string $text): ?string
    {
        $lower = Str::lower($text);

        foreach (config('ats.education_keywords', []) as $needle => $label) {
            if (Str::contains($lower, Str::lower((string) $needle))) {
                return $label;
            }
        }

        // Arabic certificate / faculty phrasing common in scanned docs
        if (preg_match('/كلية|جامعة|شهادة|بكالوريوس|ليسانس|دبلوم|ماجستير|دكتوراه/u', $text)) {
            if (preg_match('/دكتوراه|phd/iu', $text)) {
                return 'دكتوراه';
            }
            if (preg_match('/ماجستير|master|msc|mba/iu', $text)) {
                return 'ماجستير';
            }
            if (preg_match('/دبلوم|diploma/iu', $text)) {
                return 'دبلوم';
            }

            return 'بكالوريوس';
        }

        return null;
    }

    /** @return array<int, string> */
    private function extractUniversities(string $text): array
    {
        $found = [];
        $patterns = [
            '/جامعة\s+([\x{0600}-\x{06FF}]{2,}(?:\s+[\x{0600}-\x{06FF}]{2,}){0,3})/u',
            '/كلية\s+([\x{0600}-\x{06FF}]{2,}(?:\s+[\x{0600}-\x{06FF}]{2,}){0,4})/u',
        ];
        $stop = ['تحت', 'رقم', 'النتيجة', 'في', 'لشئون', 'والطلاب', 'هذه', 'من', 'إلى', 'علي', 'على'];

        foreach ($patterns as $pattern) {
            if (! preg_match_all($pattern, $text, $m, PREG_SET_ORDER)) {
                continue;
            }
            foreach ($m as $match) {
                $prefix = str_starts_with($match[0], 'كلية') ? 'كلية' : 'جامعة';
                $words = preg_split('/\s+/u', trim($match[1])) ?: [];
                $kept = [];
                foreach ($words as $word) {
                    if (in_array($word, $stop, true)) {
                        break;
                    }
                    $kept[] = $word;
                    if (count($kept) >= 3) {
                        break;
                    }
                }
                if ($kept === []) {
                    continue;
                }
                $found[] = $prefix.' '.implode(' ', $kept);
            }
        }

        if (preg_match_all('/\b([A-Z][a-zA-Z]+(?:\s+[A-Z][a-zA-Z]+){0,3}\s+University)\b/', $text, $m)) {
            $found = array_merge($found, $m[1]);
        }

        return array_values(array_unique($found));
    }

    /** @return array<int, string> */
    private function extractNationalIds(string $text): array
    {
        // Egyptian national ID is 14 digits; OCR may insert spaces.
        preg_match_all('/\b(?:\d[\s\-]*){13,16}\b/u', $text, $m);
        $ids = [];
        foreach ($m[0] ?? [] as $raw) {
            $digits = preg_replace('/\D+/', '', $raw) ?? '';
            if (strlen($digits) === 14) {
                $ids[] = $digits;
            }
        }

        return array_values(array_unique($ids));
    }

    private function extractCandidateName(string $text): ?string
    {
        $patterns = [
            '/السيد\s*\/?\s*([\x{0600}-\x{06FF}\s]{5,60})/u',
            '/الاسم\s*[:：]\s*([\x{0600}-\x{06FF}\s]{5,60})/u',
            '/name\s*[:：]\s*([A-Za-z][A-Za-z\s\.\-]{4,60})/iu',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $m)) {
                $name = trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
                $name = trim($name, " \t\n\r\0\x0B-_/\\|");
                if (mb_strlen($name) >= 5) {
                    return Str::limit($name, 80, '');
                }
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

        return Str::limit($clean, 500);
    }

    /** @return array<int, string> */
    private function extractLinks(string $text): array
    {
        preg_match_all('#https?://[^\s<>"\']+#i', $text, $m);
        $links = array_map(fn ($u) => rtrim($u, '.,);'), $m[0] ?? []);

        return array_values(array_unique($links));
    }
}
