<?php

/**
 * Login Feature Tests — Tests the login and logout functionality.
 *
 * Verifies:
 * - Login page accessibility (guests can see it, logged-in users are redirected away)
 * - Successful login with correct credentials (email + password)
 * - Failed login with wrong password or unknown email
 * - Required field validation (email and password are mandatory)
 * - Logout clears the session and redirects to home
 */

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ===========================================
// LOGIN PAGE TESTS
// ===========================================

/** Guests (not logged in) can see the login page */
test('login page is accessible to guests', function (): void {
    $this->get(route('login'))
        ->assertStatus(200);
});

/** Already logged-in users are redirected away from the login page */
test('authenticated users are redirected from login page', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect();
});

// ===========================================
// LOGIN ATTEMPT TESTS
// ===========================================

/** Correct email + password should log the user in and redirect to ideas */
test('user can login with valid credentials', function (): void {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password', // Default password from UserFactory
    ])->assertRedirect(route('idea.index'));

    $this->assertAuthenticated(); // Confirm user is now logged in
});

/** Wrong password should show an error and NOT log the user in */
test('login fails with invalid password', function (): void {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('password');

    $this->assertGuest(); // Confirm user is still NOT logged in
});

/** Non-existent email should show an error */
test('login fails with unknown email', function (): void {
    $this->post(route('login.store'), [
        'email' => 'nobody@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors('password');

    $this->assertGuest();
});

// ===========================================
// VALIDATION TESTS
// ===========================================

/** Email field is required */
test('login requires an email', function (): void {
    $this->post(route('login.store'), [
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

/** Password field is required */
test('login requires a password', function (): void {
    $this->post(route('login.store'), [
        'email' => 'john@example.com',
    ])->assertSessionHasErrors('password');
});

// ===========================================
// LOGOUT TEST
// ===========================================

/** Logging out should clear the session and redirect to the homepage */
test('user can logout', function (): void {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect('/');

    $this->assertGuest(); // Confirm user is no longer authenticated
});
