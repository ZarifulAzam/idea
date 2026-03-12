<?php

/**
 * Example Feature Test — A basic smoke test to verify the app is working.
 *
 * This checks that an authenticated user can access the /ideas page
 * and gets a successful (200) HTTP response.
 */

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('the application returns a successful response', function (): void {
    $response = $this->actingAs(User::factory()->create())->get('/ideas');

    $response->assertStatus(200);
});
