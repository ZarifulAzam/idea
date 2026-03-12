<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

/**
 * AppServiceProvider — Configures application-wide services and settings.
 *
 * Service Providers are the central place to configure your application.
 * This is where you set up things that should apply everywhere.
 * Laravel calls the boot() method after all services are registered.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This runs BEFORE boot(). Used to bind things into the service container.
     * Currently unused in this app.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * This runs AFTER all services are registered.
     * Here we configure how Eloquent models behave throughout the app:
     *
     * - Model::unguard() — Disables mass assignment protection globally.
     *   This means any field can be set via create()/update() without
     *   needing to list it in $fillable. Safe when you validate input.
     *
     * - Model::shouldBeStrict() — In development, this throws errors for:
     *   - Accessing attributes that don't exist (catches typos)
     *   - Lazy loading relationships (catches N+1 query problems)
     *   - Assigning non-fillable attributes (if guard is on)
     *
     * - Model::automaticallyEagerLoadRelationships() — Smart loading:
     *   Laravel will automatically eager-load relationships when it detects
     *   they'll be needed, preventing N+1 query performance issues.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
    }
}
