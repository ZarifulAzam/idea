<?php

/**
 * Service Providers Registration — Lists all service providers for the app.
 *
 * Service providers are loaded automatically by Laravel on every request.
 * They configure services, register bindings, and bootstrap the application.
 *
 * In Laravel 12, this file replaces the old config/app.php 'providers' array.
 */

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
];
