<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory — Generates fake User data for testing and seeding.
 *
 * Creates dummy user records. Used in tests like:
 *   User::factory()->create()          — creates and saves to database
 *   User::factory()->make()            — creates but does NOT save
 *   User::factory()->unverified()      — creates with no email verification
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Cache the hashed password so it's only computed once.
     *
     * Hashing is slow by design (for security), so we reuse the
     * same hash across all factory-created users for speed.
     */
    protected static ?string $password;

    /**
     * Define the default fake data for a User.
     *
     * - name: A random full name like "John Smith"
     * - email: A unique random email like "john@example.com"
     * - email_verified_at: Set to now() (email is verified by default)
     * - password: Hashed version of "password" (the default test password)
     * - remember_token: A random token for "remember me" functionality
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * State: Create a user whose email is NOT verified.
     *
     * Usage: User::factory()->unverified()->create()
     * Sets email_verified_at to null.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
