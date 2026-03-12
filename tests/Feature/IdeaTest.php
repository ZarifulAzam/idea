<?php

/**
 * Idea Feature Tests — Tests all CRUD operations for Ideas.
 *
 * These tests verify that:
 * - Guests are redirected to login
 * - Authenticated users can manage their own ideas
 * - Users CANNOT access other users' ideas (authorization)
 * - Validation rules work correctly
 * - Steps and images are handled properly
 *
 * uses(TestCase, RefreshDatabase) means:
 * - TestCase: provides Laravel HTTP testing methods
 * - RefreshDatabase: resets the database before each test (clean slate)
 */

declare(strict_types=1);

use App\IdeaStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// ===========================================
// INDEX TESTS — Listing ideas
// ===========================================

/** Guest (not logged in) should be sent to the login page */
test('guest is redirected from ideas index', function (): void {
    $this->get(route('idea.index'))
        ->assertRedirect(route('login'));
});

/** Logged-in user should see the ideas page successfully */
test('authenticated user can view ideas index', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('idea.index'))
        ->assertStatus(200);
});

/** Users should only see their OWN ideas, not other users' ideas */
test('user only sees their own ideas on index', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $myIdea = Idea::factory()->for($user)->create();
    $otherIdea = Idea::factory()->for($otherUser)->create();

    $this->actingAs($user)
        ->get(route('idea.index'))
        ->assertSee($myIdea->title)
        ->assertDontSee($otherIdea->title);
});

/** The ?status=pending filter should only show pending ideas */
test('ideas index can be filtered by status', function (): void {
    $user = User::factory()->create();

    $pendingIdea = Idea::factory()->for($user)->create(['status' => IdeaStatus::PENDING]);
    $completedIdea = Idea::factory()->for($user)->create(['status' => IdeaStatus::COMPLETED]);

    $this->actingAs($user)
        ->get(route('idea.index', ['status' => 'pending']))
        ->assertSee($pendingIdea->title)
        ->assertDontSee($completedIdea->title);
});

// ===========================================
// STORE TESTS — Creating ideas
// ===========================================

/** Creating an idea should save it to the database and redirect */
test('user can create an idea', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'My New Idea',
            'description' => 'A great description',
            'status' => IdeaStatus::PENDING->value,
        ])
        ->assertRedirect(route('idea.index'));

    expect(Idea::where('title', 'My New Idea')->where('user_id', $user->id)->exists())->toBeTrue();
});

/** Steps should be created along with the idea when provided */
test('idea creation creates steps when provided', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'Idea With Steps',
            'status' => IdeaStatus::PENDING->value,
            'steps' => [
                ['description' => 'Step one', 'completed' => false],
                ['description' => 'Step two', 'completed' => false],
            ],
        ]);

    $idea = Idea::where('title', 'Idea With Steps')->first();

    expect($idea->steps)->toHaveCount(2);
});

/** Uploaded images should be stored on disk and path saved to DB */
test('idea creation stores an uploaded image', function (): void {
    Storage::fake('public'); // Use a fake disk so no real files are created
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('idea.store'), [
            'title' => 'Idea With Image',
            'status' => IdeaStatus::PENDING->value,
            'image' => UploadedFile::fake()->create('idea.jpg', 100, 'image/jpeg'),
        ]);

    $idea = Idea::where('title', 'Idea With Image')->first();

    expect($idea->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($idea->image_path);
});

/** Title is required — validation should fail without it */
test('idea creation requires a title', function (): void {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'status' => IdeaStatus::PENDING->value,
        ])
        ->assertSessionHasErrors('title');
});

/** Status must be a valid enum value (not random text) */
test('idea creation requires a valid status', function (): void {
    $this->actingAs(User::factory()->create())
        ->post(route('idea.store'), [
            'title' => 'My Idea',
            'status' => 'invalid_status',
        ])
        ->assertSessionHasErrors('status');
});

/** Guests cannot create ideas — should be redirected to login */
test('guest cannot create an idea', function (): void {
    $this->post(route('idea.store'), [
        'title' => 'Sneaky Idea',
        'status' => IdeaStatus::PENDING->value,
    ])->assertRedirect(route('login'));
});

// ===========================================
// SHOW TESTS — Viewing a single idea
// ===========================================

/** Owner can view their own idea's detail page */
test('user can view their own idea', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertStatus(200);
});

/** Other users get 403 Forbidden when trying to view someone else's idea */
test('user cannot view another user\'s idea', function (): void {
    $idea = Idea::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('idea.show', $idea))
        ->assertForbidden();
});

/** Guests are sent to login when trying to view an idea */
test('guest is redirected from idea show', function (): void {
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))
        ->assertRedirect(route('login'));
});

// ===========================================
// UPDATE TESTS — Editing ideas
// ===========================================

/** Owner can update their idea's title and status */
test('user can update their own idea', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $this->actingAs($user)
        ->patch(route('idea.update', $idea), [
            'title' => 'Updated Title',
            'status' => IdeaStatus::IN_PROGRESS->value,
        ])
        ->assertRedirect();

    expect($idea->fresh()->title)->toBe('Updated Title');
    expect($idea->fresh()->status)->toBe(IdeaStatus::IN_PROGRESS);
});

/** Updating steps replaces all old steps with the new ones */
test('idea update replaces steps', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();
    $idea->steps()->createMany([
        ['description' => 'Old step', 'completed' => false],
    ]);

    $this->actingAs($user)
        ->patch(route('idea.update', $idea), [
            'title' => $idea->title,
            'status' => $idea->status->value,
            'steps' => [
                ['description' => 'New step one', 'completed' => false],
                ['description' => 'New step two', 'completed' => false],
            ],
        ]);

    expect($idea->fresh()->steps)->toHaveCount(2);
    expect($idea->fresh()->steps->first()->description)->toBe('New step one');
});

/** Other users get 403 when trying to update someone else's idea */
test('user cannot update another user\'s idea', function (): void {
    $idea = Idea::factory()->create();

    $this->actingAs(User::factory()->create())
        ->patch(route('idea.update', $idea), [
            'title' => 'Hacked Title',
            'status' => IdeaStatus::PENDING->value,
        ])
        ->assertForbidden();
});

/** Guests cannot update ideas */
test('guest cannot update an idea', function (): void {
    $idea = Idea::factory()->create();

    $this->patch(route('idea.update', $idea), [
        'title' => 'Hacked Title',
        'status' => IdeaStatus::PENDING->value,
    ])->assertRedirect(route('login'));
});

// ===========================================
// DESTROY TESTS — Deleting ideas
// ===========================================

/** Owner can delete their idea; it should be removed from the database */
test('user can delete their own idea', function (): void {
    $user = User::factory()->create();
    $idea = Idea::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('idea.destroy', $idea))
        ->assertRedirect(route('idea.index'));

    expect(Idea::find($idea->id))->toBeNull();
});

/** Other users get 403 when trying to delete someone else's idea */
test('user cannot delete another user\'s idea', function (): void {
    $idea = Idea::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('idea.destroy', $idea))
        ->assertForbidden();
});

/** Guests cannot delete ideas */
test('guest cannot delete an idea', function (): void {
    $idea = Idea::factory()->create();

    $this->delete(route('idea.destroy', $idea))
        ->assertRedirect(route('login'));
});
