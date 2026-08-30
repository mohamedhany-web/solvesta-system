<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvStorageService
{
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
        $disk = $this->diskName();
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $safeName = $safeName !== '' ? $safeName : 'cv';
        $filename = $safeName.'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();

        $path = trim($directory, '/').'/'.$filename;

        Storage::disk($disk)->putFileAs(
            trim($directory, '/'),
            $file,
            $filename
        );

        return $path;
    }

    /**
     * Resolve a download/view URL for a stored CV path (R2 signed URL or local public URL).
     */
    public function url(?string $path, int $minutes = 60): ?string
    {
        if (! $path) {
            return null;
        }

        // Legacy files previously stored on the public disk.
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        $disk = $this->diskName();

        try {
            if ($disk === 'r2' || $disk === 's3') {
                return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes($minutes));
            }

            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->url($path);
            }
        } catch (\Throwable $e) {
            Log::warning('CV URL resolve failed', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);

            try {
                return Storage::disk($disk)->url($path);
            } catch (\Throwable) {
                // fall through
            }
        }

        return asset('storage/'.$path);
    }
}
