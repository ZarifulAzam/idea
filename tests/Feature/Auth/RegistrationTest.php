<?php

/**
 * Registration Feature Tests — Tests new user registration.
 *
 * Verifies:
 * - Registration page accessibility (guests see it, logged-in users are redirected away)
 * - Successful registration creates user, logs them in, and redirects to ideas
 * - All validation rules: name required + min 3 chars, email required + valid + unique, password required
 */

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ===========================================
// REGISTRATION PAGE TESTS
// ===========================================

/** Guests can see the registration page */
test('registration page is accessible to guests', function (): void {
    $this->get(route('register'))
        ->assertStatus(200);
});

/** Already logged-in users are redirected away from registration */
test('authenticated users are redirected from register page', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('register'))
        ->assertRedirect();
});

// ===========================================
// SUCCESSFUL REGISTRATION
// ===========================================

/** Valid data creates a new user, logs them in, and redirects to ideas */
test('user can register with valid data', function (): void {
    $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
    ])->assertRedirect(route('idea.index'));

    // Verify the user was actually created in the database
    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
    // Verify the user was automatically logged in
    $this->assertAuthenticated();
});

// ===========================================
// VALIDATION TESTS
// ===========================================

/** Name field is required */
test('registration requires a name', function (): void {
    $this->post(route('register.store'), [
        'email' => 'john@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors('name');
});

/** Name must be at least 3 characters long (e.g., "Jo" fails) */
test('registration name must be at least 3 characters', function (): void {
    $this->post(route('register.store'), [
        'name' => 'Jo',
        'email' => 'john@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors('name');
});

/** Email must be a valid email format (e.g., "not-an-email" fails) */
test('registration requires a valid email', function (): void {
    $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'not-an-email',
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

/** Email must not already exist in the database */
test('registration email must be unique', function (): void {
    User::factory()->create(['email' => 'john@example.com']);

    $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

/** Password field is required */
test('registration requires a password', function (): void {
    $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ])->assertSessionHasErrors('password');
});
