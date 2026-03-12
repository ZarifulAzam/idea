<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — The main seeder that populates the database with initial data.
 *
 * Run with: php artisan db:seed
 * Or reset + seed: php artisan migrate:fresh --seed
 *
 * This creates a test user you can log in with during development:
 * - Email: test@example.com
 * - Password: password (the factory default)
 */
class DatabaseSeeder extends Seeder
{
    // Prevents model events from firing during seeding (faster)
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Creates one test user with known credentials for local development.
     */
    public function run(): void
    {
        // User::factory(10)->create(); // Uncomment to create 10 random users

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
