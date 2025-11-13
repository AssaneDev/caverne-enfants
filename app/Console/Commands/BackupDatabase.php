<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database
                            {--keep-days=30 : Number of days to keep backups}
                            {--list : List all available backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup and upload it to R2 storage';

    /**
     * The backup service instance
     *
     * @var BackupService
     */
    protected BackupService $backupService;

    /**
     * Create a new command instance.
     */
    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // If --list option is provided, list all backups
        if ($this->option('list')) {
            return $this->listBackups();
        }

        // Display header
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║   Database Backup to R2 Storage        ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        // Display configuration
        $dbName = config('database.connections.mysql.database');
        $dbHost = config('database.connections.mysql.host');
        $r2Bucket = config('filesystems.disks.r2.bucket');
        $keepDays = (int) $this->option('keep-days');

        $this->line("Database: <fg=cyan>{$dbName}</>@<fg=cyan>{$dbHost}</>");
        $this->line("R2 Bucket: <fg=cyan>{$r2Bucket}</>");
        $this->line("Retention: <fg=cyan>{$keepDays} days</>");
        $this->newLine();

        // Confirm before proceeding
        if (!$this->confirm('Do you want to proceed with the backup?', true)) {
            $this->warn('Backup cancelled.');
            return self::FAILURE;
        }

        $this->newLine();

        // Start backup process
        $this->info('[1/4] Creating database dump...');
        $startTime = microtime(true);

        // Create progress bar (indeterminate)
        $progressBar = $this->output->createProgressBar();
        $progressBar->setFormat(' %message%');
        $progressBar->setMessage('Dumping database...');
        $progressBar->start();

        // Perform backup
        $result = $this->backupService->backup($keepDays);

        $progressBar->finish();
        $this->newLine();

        $duration = round(microtime(true) - $startTime, 2);

        // Display result
        if ($result['success']) {
            $this->newLine();
            $this->info('✓ Database dump created successfully');
            $this->info('[2/4] Compressing backup...');
            $this->info('✓ Backup compressed successfully');
            $this->info('[3/4] Uploading to R2...');
            $this->info('✓ Backup uploaded to R2 successfully');
            $this->info('[4/4] Cleaning old backups...');
            $this->info('✓ Old backups cleaned');
            $this->newLine();

            // Display summary
            $this->info('╔════════════════════════════════════════╗');
            $this->info('║           Backup Summary               ║');
            $this->info('╚════════════════════════════════════════╝');
            $this->newLine();
            $this->line("File: <fg=green>{$result['file']}</>");
            $this->line("Size: <fg=green>{$result['size']}</>");
            $this->line("Path: <fg=green>{$result['path']}</>");
            $this->line("Duration: <fg=green>{$duration}s</>");
            $this->newLine();
            $this->info('✓ Backup completed successfully!');

            return self::SUCCESS;
        } else {
            $this->newLine();
            $this->error('✗ Backup failed!');
            $this->error($result['message']);
            $this->newLine();
            $this->warn('Please check the logs for more details.');

            return self::FAILURE;
        }
    }

    /**
     * List all available backups
     */
    protected function listBackups(): int
    {
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║        Available Backups               ║');
        $this->info('╚════════════════════════════════════════╝');
        $this->newLine();

        $backups = $this->backupService->listBackups();

        if (empty($backups)) {
            $this->warn('No backups found.');
            return self::SUCCESS;
        }

        // Prepare table data
        $tableData = [];
        foreach ($backups as $backup) {
            $tableData[] = [
                $backup['file'],
                $backup['date'],
                $backup['size'],
                $backup['age_days'] . ' days ago'
            ];
        }

        // Display table
        $this->table(
            ['Filename', 'Date', 'Size', 'Age'],
            $tableData
        );

        $this->newLine();
        $this->info('Total backups: ' . count($backups));

        return self::SUCCESS;
    }
}
