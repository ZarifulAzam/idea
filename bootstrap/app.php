<?php

/**
 * Application Bootstrap File — The entry point for configuring the Laravel app.
 *
 * This is where Laravel 12 configures:
 * - Routing: which files define routes (web routes, console commands, health check)
 * - Middleware: HTTP middleware pipeline (authentication, CSRF, etc.)
 * - Exceptions: how to handle and report errors
 *
 * In Laravel 12, this file replaces the old Kernel files.
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',       // Web routes (browser requests)
        commands: __DIR__.'/../routes/console.php', // Artisan console commands
        health: '/up',                             // Health check endpoint at /up
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware here if needed
        // Example: $middleware->append(MyMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Customize exception handling here if needed
        // Example: $exceptions->render(function (NotFoundHttpException $e) { ... });
    })->create();
