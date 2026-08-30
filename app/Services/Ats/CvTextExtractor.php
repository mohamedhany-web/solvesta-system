<?php

namespace App\Services\Ats;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;

class CvTextExtractor
{
    public function extractFromContents(string $binary, string $extension): string
    {
        $extension = Str::lower($extension);

        return match ($extension) {
            'pdf' => $this->fromPdf($binary),
            'docx' => $this->fromDocx($binary),
            'doc' => $this->fromDoc($binary),
            default => $this->fromPlain($binary),
        };
    }

    public function extractFromDisk(string $disk, string $path): string
    {
        // Prefer direct R2/public read via CvStorageService when possible.
        try {
            $binary = app(\App\Services\CvStorageService::class)->get($path);
        } catch (\Throwable) {
            $binary = Storage::disk($disk)->get($path);
        }

        return $this->extractFromContents($binary, pathinfo($path, PATHINFO_EXTENSION));
    }

    public function fromPdf(string $binary): string
    {
        try {
            $parser = new PdfParser;
            $pdf = $parser->parseContent($binary);

            return $this->normalize($pdf->getText() ?? '');
        } catch (\Throwable $e) {
            Log::warning('PDF parse failed', ['error' => $e->getMessage()]);

            return '';
        }
    }

    public function fromDocx(string $binary): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'docx');
        file_put_contents($tmp, $binary);

        try {
            $zip = new \ZipArchive;
            if ($zip->open($tmp) !== true) {
                return '';
            }
            $xml = $zip->getFromName('word/document.xml') ?: '';
            $zip->close();

            $text = strip_tags(str_replace('</w:p>', "\n", $xml));

            return $this->normalize(html_entity_decode($text, ENT_QUOTES | ENT_XML1));
        } catch (\Throwable $e) {
            Log::warning('DOCX parse failed', ['error' => $e->getMessage()]);

            return '';
        } finally {
            @unlink($tmp);
        }
    }

    public function fromDoc(string $binary): string
    {
        // Best-effort for legacy .doc: strip non-printables.
        $text = preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{0600}-\x{06FF}]/u', ' ', $binary) ?? '';

        return $this->normalize($text);
    }

    public function fromPlain(string $binary): string
    {
        return $this->normalize($binary);
    }

    private function normalize(string $text): string
    {
        $text = str_replace("\0", '', $text);
        $text = preg_replace("/[ \t]+/u", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }
}
