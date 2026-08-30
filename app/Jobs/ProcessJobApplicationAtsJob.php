<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Services\Ats\AtsEvaluationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessJobApplicationAtsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(public int $applicationId) {}

    public function handle(AtsEvaluationService $ats): void
    {
        $application = JobApplication::find($this->applicationId);
        if (! $application) {
            return;
        }

        $ats->process($application);
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error('ProcessJobApplicationAtsJob failed', [
            'application_id' => $this->applicationId,
            'error' => $exception?->getMessage(),
        ]);

        JobApplication::where('id', $this->applicationId)->update([
            'parse_status' => 'failed',
            'parse_error' => $exception?->getMessage(),
        ]);
    }
}
