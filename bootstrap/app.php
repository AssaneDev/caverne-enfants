<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exclure les webhooks de la vérification CSRF
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            '/webhooks/*'
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Database backup - every day at 2:00 AM
        $schedule->command('backup:database')
            ->daily()
            ->at('02:00')
            ->onSuccess(function () {
                \Illuminate\Support\Facades\Log::info('Scheduled backup completed successfully');
            })
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('Scheduled backup failed');
            });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
