<?php

/**
 * Console Routes — Define custom Artisan commands.
 *
 * Artisan is Laravel's command-line tool. You can create custom
 * commands here that are run via: php artisan <command-name>
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// A simple example command: run "php artisan inspire" to see a random quote
Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
