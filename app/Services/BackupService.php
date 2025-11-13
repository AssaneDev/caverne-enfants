<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class BackupService
{
    /**
     * Create a database backup and upload it to R2
     *
     * @param int $keepDays Number of days to keep backups
     * @return array ['success' => bool, 'message' => string, 'file' => string|null]
     */
    public function backup(int $keepDays = 30): array
    {
        try {
            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Validate database configuration
            if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
                throw new Exception('Database configuration is incomplete');
            }

            // Generate backup filename with timestamp
            $timestamp = date('Y-m-d-His');
            $filename = "backup-{$timestamp}.sql";
            $compressedFilename = "{$filename}.gz";
            $tempPath = storage_path("app/temp/{$filename}");
            $compressedPath = storage_path("app/temp/{$compressedFilename}");

            // Ensure temp directory exists
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            Log::info('Starting database backup', [
                'database' => $dbName,
                'host' => $dbHost,
                'filename' => $compressedFilename
            ]);

            // Create mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s 2>&1',
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($tempPath)
            );

            // Execute mysqldump
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $errorMessage = implode("\n", $output);
                Log::error('Mysqldump failed', ['error' => $errorMessage]);
                throw new Exception("Mysqldump failed: {$errorMessage}");
            }

            // Check if dump file was created and has content
            if (!file_exists($tempPath) || filesize($tempPath) === 0) {
                throw new Exception('Dump file was not created or is empty');
            }

            $dumpSize = filesize($tempPath);
            Log::info('Database dump created', ['size' => $this->formatBytes($dumpSize)]);

            // Compress the dump file
            $this->compressFile($tempPath, $compressedPath);

            if (!file_exists($compressedPath)) {
                throw new Exception('Compressed file was not created');
            }

            $compressedSize = filesize($compressedPath);
            Log::info('Database dump compressed', [
                'original_size' => $this->formatBytes($dumpSize),
                'compressed_size' => $this->formatBytes($compressedSize),
                'compression_ratio' => round((1 - $compressedSize / $dumpSize) * 100, 2) . '%'
            ]);

            // Upload to R2
            $r2Path = "backups/{$compressedFilename}";
            $fileContents = file_get_contents($compressedPath);

            $uploaded = Storage::disk('r2')->put($r2Path, $fileContents);

            if (!$uploaded) {
                throw new Exception('Failed to upload backup to R2');
            }

            Log::info('Backup uploaded to R2', ['path' => $r2Path]);

            // Clean up temp files
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            if (file_exists($compressedPath)) {
                unlink($compressedPath);
            }

            // Clean old backups
            $this->cleanOldBackups($keepDays);

            return [
                'success' => true,
                'message' => "Backup created successfully: {$compressedFilename}",
                'file' => $compressedFilename,
                'size' => $this->formatBytes($compressedSize),
                'path' => $r2Path
            ];

        } catch (Exception $e) {
            Log::error('Backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up temp files on error
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
            if (isset($compressedPath) && file_exists($compressedPath)) {
                unlink($compressedPath);
            }

            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
                'file' => null
            ];
        }
    }

    /**
     * Compress a file using gzip
     *
     * @param string $source Source file path
     * @param string $destination Destination file path
     * @return void
     */
    protected function compressFile(string $source, string $destination): void
    {
        $bufferSize = 4096;
        $file = fopen($source, 'rb');
        $gzFile = gzopen($destination, 'wb9');

        while (!feof($file)) {
            gzwrite($gzFile, fread($file, $bufferSize));
        }

        fclose($file);
        gzclose($gzFile);
    }

    /**
     * Clean old backups based on retention policy
     *
     * @param int $keepDays Number of days to keep backups
     * @return int Number of deleted backups
     */
    protected function cleanOldBackups(int $keepDays): int
    {
        try {
            $disk = Storage::disk('r2');
            $files = $disk->files('backups');

            $cutoffDate = now()->subDays($keepDays);
            $deletedCount = 0;

            foreach ($files as $file) {
                // Extract timestamp from filename (backup-2025-11-13-143055.sql.gz)
                if (preg_match('/backup-(\d{4}-\d{2}-\d{2}-\d{6})\.sql\.gz$/', $file, $matches)) {
                    $fileDate = \DateTime::createFromFormat('Y-m-d-His', $matches[1]);

                    if ($fileDate && $fileDate->getTimestamp() < $cutoffDate->getTimestamp()) {
                        $disk->delete($file);
                        $deletedCount++;
                        Log::info('Deleted old backup', ['file' => $file]);
                    }
                }
            }

            if ($deletedCount > 0) {
                Log::info("Cleaned old backups", ['deleted_count' => $deletedCount, 'keep_days' => $keepDays]);
            }

            return $deletedCount;

        } catch (Exception $e) {
            Log::error('Failed to clean old backups', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * List all available backups
     *
     * @return array
     */
    public function listBackups(): array
    {
        try {
            $disk = Storage::disk('r2');
            $files = $disk->files('backups');

            $backups = [];

            foreach ($files as $file) {
                if (preg_match('/backup-(\d{4}-\d{2}-\d{2}-\d{6})\.sql\.gz$/', $file, $matches)) {
                    $fileDate = \DateTime::createFromFormat('Y-m-d-His', $matches[1]);
                    $size = $disk->size($file);

                    $backups[] = [
                        'file' => basename($file),
                        'path' => $file,
                        'date' => $fileDate ? $fileDate->format('Y-m-d H:i:s') : 'Unknown',
                        'size' => $this->formatBytes($size),
                        'size_bytes' => $size,
                        'age_days' => $fileDate ? now()->diffInDays($fileDate) : null
                    ];
                }
            }

            // Sort by date descending (newest first)
            usort($backups, function($a, $b) {
                return $b['size_bytes'] <=> $a['size_bytes'];
            });

            return $backups;

        } catch (Exception $e) {
            Log::error('Failed to list backups', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
