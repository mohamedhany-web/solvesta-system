<?php

namespace App\Console\Commands;

use App\Jobs\ProcessJobApplicationAtsJob;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Services\Ats\AtsEvaluationService;
use App\Services\Ats\CvOcrService;
use Illuminate\Console\Command;

class ReprocessAtsApplicationsCommand extends Command
{
    protected $signature = 'ats:reprocess
                            {jobPosting? : Job posting ID (optional — all jobs if omitted)}
                            {--only-pending : Only applications not yet parsed successfully}
                            {--sync : Run inline instead of queue}';

    protected $description = 'Reprocess job applications through ATS parsing + OCR';

    public function handle(AtsEvaluationService $ats, CvOcrService $ocr): int
    {
        $this->info('Tesseract: '.($ocr->tesseractAvailable() ? 'available' : 'NOT FOUND — scanned CVs may fail'));

        $jobId = $this->argument('jobPosting');
        $query = JobApplication::query()->whereNotNull('cv_path')->where('cv_path', '!=', '');

        if ($jobId) {
            $job = JobPosting::find($jobId);
            if (! $job) {
                $this->error("Job posting {$jobId} not found.");

                return self::FAILURE;
            }
            $ats->ensureCriteria($job);
            $query->where('job_posting_id', $job->id);
            $this->info("Job: {$job->title}");
        } else {
            JobPosting::query()->each(fn (JobPosting $job) => $ats->ensureCriteria($job));
        }

        if ($this->option('only-pending')) {
            $query->where(function ($q) {
                $q->whereNull('parse_status')
                    ->orWhereIn('parse_status', ['pending', 'failed', 'empty', 'scanned', 'skipped'])
                    ->orWhere(function ($q2) {
                        $q2->where('parse_status', 'parsed')
                            ->where(function ($q3) {
                                $q3->whereNull('cv_text')->orWhere('cv_text', '');
                            });
                    });
            });
        }

        $ids = $query->orderBy('id')->pluck('id');
        if ($ids->isEmpty()) {
            $this->warn('No applications to process.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($ids->count());
        $bar->start();

        foreach ($ids as $id) {
            if ($this->option('sync') || config('queue.default') === 'sync') {
                ProcessJobApplicationAtsJob::dispatchSync($id);
            } else {
                ProcessJobApplicationAtsJob::dispatch($id);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Queued/processed {$ids->count()} application(s).");

        if (! $this->option('sync') && config('queue.default') !== 'sync') {
            $this->comment('Run: php artisan queue:work --tries=3 --timeout=300');
        }

        return self::SUCCESS;
    }
}
