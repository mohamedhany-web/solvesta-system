<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvStorageService
{
    private ?S3Client $s3 = null;

    public function diskName(): string
    {
        $disk = (string) config('filesystems.cv_disk', env('CV_DISK', 'r2'));

        if ($disk === 'r2' && ! $this->r2Configured()) {
            return 'public';
        }

        return $disk !== '' ? $disk : 'public';
    }

    public function r2Configured(): bool
    {
        return filled(config('filesystems.disks.r2.key'))
            && filled(config('filesystems.disks.r2.secret'))
            && filled(config('filesystems.disks.r2.bucket'))
            && filled(config('filesystems.disks.r2.endpoint'));
    }

    /**
     * Store a CV file and return the relative path saved in the database.
     */
    public function store(UploadedFile $file, string $directory = 'careers/cv'): string
    {
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $safeName = $safeName !== '' ? $safeName : 'cv';
        $filename = $safeName.'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();
        $path = trim($directory, '/').'/'.$filename;

        if ($this->diskName() === 'r2') {
            $this->s3()->putObject([
                'Bucket' => $this->bucket(),
                'Key' => $path,
                'Body' => fopen($file->getRealPath(), 'rb'),
                'ContentType' => $file->getMimeType() ?: 'application/octet-stream',
            ]);

            return $path;
        }

        Storage::disk('public')->putFileAs(trim($directory, '/'), $file, $filename);

        return $path;
    }

    public function exists(string $path): bool
    {
        if ($this->diskName() === 'r2') {
            try {
                return $this->s3()->doesObjectExist($this->bucket(), $path);
            } catch (\Throwable $e) {
                Log::warning('R2 exists check failed', ['path' => $path, 'error' => $e->getMessage()]);
            }
        }

        return Storage::disk('public')->exists($path);
    }

    public function get(string $path): string
    {
        if ($this->diskName() === 'r2' && $this->existsOnR2($path)) {
            $result = $this->s3()->getObject([
                'Bucket' => $this->bucket(),
                'Key' => $path,
            ]);

            return (string) $result['Body'];
        }

        if (Storage::disk('public')->exists($path)) {
            return (string) Storage::disk('public')->get($path);
        }

        throw new \RuntimeException('Unable to read CV file: '.$path);
    }

    /**
     * Resolve a download/view URL for a stored CV path (R2 signed URL or local public URL).
     */
    public function url(?string $path, int $minutes = 60): ?string
    {
        if (! $path) {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        if ($this->diskName() === 'r2' && $this->r2Configured()) {
            try {
                $cmd = $this->s3()->getCommand('GetObject', [
                    'Bucket' => $this->bucket(),
                    'Key' => $path,
                ]);
                $request = $this->s3()->createPresignedRequest($cmd, '+'.$minutes.' minutes');

                return (string) $request->getUri();
            } catch (\Throwable $e) {
                Log::warning('CV URL resolve failed', [
                    'path' => $path,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return asset('storage/'.$path);
    }

    private function existsOnR2(string $path): bool
    {
        try {
            return $this->s3()->doesObjectExist($this->bucket(), $path);
        } catch (\Throwable) {
            return false;
        }
    }

    private function bucket(): string
    {
        return (string) config('filesystems.disks.r2.bucket');
    }

    private function s3(): S3Client
    {
        if ($this->s3) {
            return $this->s3;
        }

        $config = config('filesystems.disks.r2');

        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $config['region'] ?? 'auto',
            'endpoint' => $config['endpoint'],
            'use_path_style_endpoint' => (bool) ($config['use_path_style_endpoint'] ?? true),
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);

        return $this->s3;
    }
}
