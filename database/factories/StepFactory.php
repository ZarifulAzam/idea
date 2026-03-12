<?php

namespace Database\Factories;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * StepFactory — Generates fake Step data for testing and seeding.
 *
 * Creates dummy step records. Used in tests like:
 *   Step::factory()->for($idea)->create()
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Step>
 */
class StepFactory extends Factory
{
    /**
     * Define the default fake data for a Step.
     *
     * - idea_id: Creates a new fake Idea automatically (nested factory)
     * - description: A random sentence describing what to do
     * - completed: Defaults to false (not done yet)
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idea_id' => Idea::factory(),
            'description' => fake()->sentence(),
            'completed' => false,
        ];
    }
}
