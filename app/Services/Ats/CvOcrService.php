<?php

namespace App\Services\Ats;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * OCR for scanned CV PDFs (CamScanner / image-only).
 * Prefer local Tesseract; optionally fall back to OCR.space API.
 */
class CvOcrService
{
    public function extractTextFromPdf(string $pdfBinary): string
    {
        $images = $this->extractImagesFromPdf($pdfBinary);
        if ($images === []) {
            Log::info('OCR: no embedded images found in PDF');

            return '';
        }

        $chunks = [];
        foreach (array_slice($images, 0, (int) config('ats.ocr.max_pages', 4)) as $i => $image) {
            $text = $this->ocrImage($image['binary'], $image['ext']);
            if (trim($text) !== '') {
                $chunks[] = $text;
            }
            Log::info('OCR page done', ['page' => $i + 1, 'chars' => mb_strlen($text)]);
        }

        return $this->normalize(implode("\n\n", $chunks));
    }

    /**
     * @return array<int, array{binary:string,ext:string}>
     */
    public function extractImagesFromPdf(string $pdfBinary): array
    {
        $images = [];

        if (preg_match_all('/\xFF\xD8\xFF[\s\S]*?\xFF\xD9/', $pdfBinary, $m)) {
            foreach ($m[0] as $jpeg) {
                if (strlen($jpeg) > 8000) {
                    $images[] = ['binary' => $jpeg, 'ext' => 'jpg'];
                }
            }
        }

        if (preg_match_all('/\x89PNG\r\n\x1a\n[\s\S]*?IEND\xaeB`\x82/', $pdfBinary, $m)) {
            foreach ($m[0] as $png) {
                if (strlen($png) > 8000) {
                    $images[] = ['binary' => $png, 'ext' => 'png'];
                }
            }
        }

        $unique = [];
        $seen = [];
        foreach ($images as $img) {
            $key = md5(substr($img['binary'], 0, 2048).'|'.strlen($img['binary']));
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $img;
        }

        usort($unique, fn ($a, $b) => strlen($b['binary']) <=> strlen($a['binary']));

        return $unique;
    }

    public function ocrImage(string $binary, string $ext = 'jpg'): string
    {
        // Shared hosting often has no Tesseract — prefer API first when binary missing.
        if (! $this->tesseractAvailable()) {
            return $this->ocrWithApi($binary, $ext);
        }

        $text = $this->ocrWithTesseract($binary, $ext);
        if (mb_strlen(trim($text)) >= 40) {
            return $text;
        }

        $apiText = $this->ocrWithApi($binary, $ext);
        if (mb_strlen(trim($apiText)) > mb_strlen(trim($text))) {
            return $apiText;
        }

        return $text;
    }

    public function tesseractAvailable(): bool
    {
        return $this->tesseractBinary() !== null;
    }

    private function ocrWithTesseract(string $binary, string $ext): string
    {
        $bin = $this->tesseractBinary();
        if (! $bin) {
            return '';
        }

        $tmpImg = tempnam(sys_get_temp_dir(), 'cvocr_').'.'.$ext;
        $tmpOutBase = tempnam(sys_get_temp_dir(), 'cvocr_out_');
        @unlink($tmpOutBase);
        file_put_contents($tmpImg, $binary);
        $prepared = null;

        try {
            $prepared = $this->preprocessImage($tmpImg);
            $input = $prepared ?: $tmpImg;
            $lang = $this->resolveLang();
            $tessdataDir = $this->tessdataDir();

            $cmd = '"'.$bin.'" '
                .escapeshellarg($input).' '
                .escapeshellarg($tmpOutBase)
                .' -l '.escapeshellarg($lang)
                .' --psm '.(int) config('ats.ocr.psm', 6);

            if ($tessdataDir) {
                $cmd .= ' --tessdata-dir '.escapeshellarg($tessdataDir);
            }

            $output = [];
            $code = 0;
            exec($cmd.' 2>&1', $output, $code);

            $txtFile = $tmpOutBase.'.txt';
            $text = is_file($txtFile) ? (string) file_get_contents($txtFile) : '';

            if ($code !== 0 && $text === '') {
                Log::warning('Tesseract OCR failed', [
                    'code' => $code,
                    'output' => implode("\n", $output),
                    'lang' => $lang,
                ]);
            }

            return $this->normalize($text);
        } catch (\Throwable $e) {
            Log::warning('Tesseract OCR exception', ['error' => $e->getMessage()]);

            return '';
        } finally {
            @unlink($tmpImg);
            @unlink($tmpOutBase.'.txt');
            if (is_string($prepared) && is_file($prepared)) {
                @unlink($prepared);
            }
        }
    }

    private function resolveLang(): string
    {
        $wanted = (string) config('ats.ocr.tesseract_lang', 'eng+ara');
        $dirs = array_filter([
            $this->tessdataDir(),
            dirname($this->tesseractBinary() ?: '').DIRECTORY_SEPARATOR.'tessdata',
        ]);
        $parts = preg_split('/\+/', $wanted) ?: ['eng'];
        $available = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            foreach ($dirs as $dir) {
                if (is_file($dir.DIRECTORY_SEPARATOR.$part.'.traineddata')) {
                    $available[] = $part;
                    break;
                }
            }
        }

        if ($available === []) {
            return 'eng';
        }

        return implode('+', array_unique($available));
    }

    private function tessdataDir(): ?string
    {
        $configured = config('ats.ocr.tessdata_dir');
        if (is_string($configured) && $configured !== '' && is_dir($configured)) {
            return $configured;
        }

        $local = storage_path('app/tessdata');
        if (is_dir($local) && (is_file($local.'/eng.traineddata') || is_file($local.'/ara.traineddata'))) {
            return $local;
        }

        return null;
    }

    private function ocrWithApi(string $binary, string $ext): string
    {
        if (! config('ats.ocr.api_enabled', true)) {
            return '';
        }

        $apiKey = (string) (config('ats.ocr.api_key') ?: 'helloworld');

        try {
            $response = Http::timeout((int) config('ats.ocr.api_timeout', 90))
                ->asMultipart()
                ->post('https://api.ocr.space/parse/image', [
                    [
                        'name' => 'apikey',
                        'contents' => $apiKey,
                    ],
                    [
                        'name' => 'language',
                        'contents' => (string) config('ats.ocr.api_language', 'eng'),
                    ],
                    [
                        'name' => 'isOverlayRequired',
                        'contents' => 'false',
                    ],
                    [
                        'name' => 'OCREngine',
                        'contents' => '2',
                    ],
                    [
                        'name' => 'scale',
                        'contents' => 'true',
                    ],
                    [
                        'name' => 'file',
                        'contents' => $binary,
                        'filename' => 'cv.'.$ext,
                    ],
                ]);

            if (! $response->ok()) {
                Log::warning('OCR API HTTP error', ['status' => $response->status()]);

                return '';
            }

            $json = $response->json();
            if (! empty($json['IsErroredOnProcessing'])) {
                Log::warning('OCR API processing error', ['msg' => $json['ErrorMessage'] ?? $json]);

                return '';
            }

            $parts = [];
            foreach ($json['ParsedResults'] ?? [] as $row) {
                $parts[] = (string) ($row['ParsedText'] ?? '');
            }

            return $this->normalize(implode("\n", $parts));
        } catch (\Throwable $e) {
            Log::warning('OCR API exception', ['error' => $e->getMessage()]);

            return '';
        }
    }

    private function preprocessImage(string $path): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        try {
            $data = file_get_contents($path);
            $src = @imagecreatefromstring($data ?: '');
            if (! $src) {
                return null;
            }

            $w = imagesx($src);
            $h = imagesy($src);
            $scale = max($w, $h) < 1400 ? (1400 / max($w, $h)) : 1.0;
            $nw = (int) max(1, round($w * $scale));
            $nh = (int) max(1, round($h * $scale));
            $dst = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            // Prefer stronger contrast for phone-camera scans.
            imagefilter($dst, IMG_FILTER_GRAYSCALE);
            imagefilter($dst, IMG_FILTER_CONTRAST, -25);
            imagefilter($dst, IMG_FILTER_BRIGHTNESS, 8);

            $out = tempnam(sys_get_temp_dir(), 'cvocr_prep_').'.png';
            imagepng($dst, $out, 6);
            imagedestroy($src);
            imagedestroy($dst);

            return $out;
        } catch (\Throwable $e) {
            Log::warning('OCR preprocess failed', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function tesseractBinary(): ?string
    {
        $configured = config('ats.ocr.tesseract_path');
        if (is_string($configured) && $configured !== '' && is_file($configured)) {
            return $configured;
        }

        $candidates = [
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            '/usr/bin/tesseract',
            '/usr/local/bin/tesseract',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        $which = PHP_OS_FAMILY === 'Windows' ? 'where tesseract' : 'command -v tesseract';
        $out = [];
        @exec($which, $out, $code);
        if ($code === 0 && ! empty($out[0]) && is_file(trim($out[0]))) {
            return trim($out[0]);
        }

        return null;
    }

    private function normalize(string $text): string
    {
        $text = str_replace("\0", '', $text);
        $text = preg_replace('/CamScanner/i', ' ', $text) ?? $text;
        $text = preg_replace("/[ \t]+/u", ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;

        return trim($text);
    }
}
