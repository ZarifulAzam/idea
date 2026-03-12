<?php

/**
 * Profile Feature Tests — Tests updating user profile information.
 *
 * Verifies:
 * - User can update name and email without changing their password
 * - User can update their password along with other profile fields
 */

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

/** Updating name/email should work WITHOUT changing the password */
test('authenticated user can update profile without changing password', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->assertRedirect(route('profile.edit'));

    $user->refresh(); // Reload the user from DB to see the changes

    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
    // Password should remain the same since we didn't send a new one
    expect(Hash::check('old-password', $user->password))->toBeTrue();
});

/** Sending a new password should update the stored password hash */
test('authenticated user can update profile password', function (): void {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'new-password',
        ])
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    // Verify the new password is now stored
    expect(Hash::check('new-password', $user->password))->toBeTrue();
});
