<?php

/**
 * Idea Image Feature Tests — Tests removing an idea's featured image.
 *
 * Verifies that:
 * - Users can remove the image from their idea (file deleted + DB cleared)
 * - Guests cannot remove images (redirected to login)
 */

declare(strict_types=1);

use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/** Owner can remove their idea's image — file should be deleted, path set to null */
test('user can remove the image from their idea', function (): void {
    Storage::fake('public'); // Fake the disk so no real files are touched
    Storage::disk('public')->put('ideas/test.jpg', 'fake content'); // Create a fake image file

    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create(['image_path' => 'ideas/test.jpg']);

    $this->actingAs($user)
        ->delete(route('idea.image.destroy', $idea))
        ->assertRedirect();

    // Verify the image path is cleared in the database
    expect($idea->fresh()->image_path)->toBeNull();
    // Verify the file was actually deleted from disk
    Storage::disk('public')->assertMissing('ideas/test.jpg');
});

/** Guests cannot remove images — should be redirected to login */
test('guest cannot remove an idea image', function (): void {
    $idea = Idea::factory()->create(['image_path' => 'ideas/test.jpg']);

    $this->delete(route('idea.image.destroy', $idea))
        ->assertRedirect(route('login'));
});
