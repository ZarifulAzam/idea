<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * IdeaFactory — Generates fake Idea data for testing and seeding.
 *
 * Factories create realistic dummy data so you don't have to manually
 * create test records. Used in tests like: Idea::factory()->create()
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the default fake data for an Idea.
     *
     * - user_id: Creates a new fake User automatically (nested factory)
     * - title: A random sentence like "The quick brown fox jumps."
     * - description: A random paragraph of text
     * - links: An array with one random URL
     *
     * Status defaults to 'pending' (set in the Idea model's $attributes).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'links' => [fake()->url()],
        ];
    }
}
