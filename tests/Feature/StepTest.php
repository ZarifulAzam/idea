<?php

/**
 * Step Feature Tests — Tests step toggle (complete/incomplete) functionality.
 *
 * Steps are sub-tasks of an idea. The main action tested here is
 * toggling a step's completed status (like checking/unchecking a checkbox).
 */

declare(strict_types=1);

use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);
use App\Models\Step;
use App\Models\User;

/** Guests should be redirected to login when trying to update a step */
test('guest is redirected when updating a step', function (): void {
    $step = Step::factory()->create();

    $this->patch(route('step.update', $step))
        ->assertRedirect(route('login'));
});

/** Clicking an incomplete step should mark it as completed */
test('user can toggle a step to completed', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();
    $step = Step::factory()->for($idea)->create(['completed' => false]);

    $this->actingAs($user)
        ->patch(route('step.update', $step));

    expect($step->fresh()->completed)->toBeTrue();
});

/** Clicking a completed step should mark it as incomplete (toggle back) */
test('user can toggle a completed step back to incomplete', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();
    $step = Step::factory()->for($idea)->create(['completed' => true]);

    $this->actingAs($user)
        ->patch(route('step.update', $step));

    expect($step->fresh()->completed)->toBeFalse();
});
