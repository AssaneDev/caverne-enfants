<?php

namespace App\Jobs;

use App\Services\BackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of days to keep backups
     *
     * @var int
     */
    protected int $keepDays;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(int $keepDays = 30)
    {
        $this->keepDays = $keepDays;
    }

    /**
     * Execute the job.
     */
    public function handle(BackupService $backupService): void
    {
        Log::info('BackupDatabaseJob started', ['keep_days' => $this->keepDays]);

        $result = $backupService->backup($this->keepDays);

        if ($result['success']) {
            Log::info('BackupDatabaseJob completed successfully', [
                'file' => $result['file'],
                'size' => $result['size'],
                'path' => $result['path']
            ]);
        } else {
            Log::error('BackupDatabaseJob failed', [
                'message' => $result['message']
            ]);

            // Throw exception to trigger retry
            throw new \Exception($result['message']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('BackupDatabaseJob failed after all retries', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // You could send a notification here
        // Notification::route('mail', config('mail.admin_email'))
        //     ->notify(new BackupFailedNotification($exception));
    }
}
