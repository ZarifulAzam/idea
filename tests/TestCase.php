<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base Test Case — The parent class for all feature tests.
 *
 * All feature tests extend this class (via Pest's uses() function).
 * It provides Laravel's testing helpers like:
 * - $this->get(), $this->post(), $this->patch(), $this->delete() for HTTP testing
 * - $this->actingAs($user) to simulate an authenticated user
 * - $this->assertAuthenticated() / $this->assertGuest() for auth checks
 *
 * You can add shared setup logic here (e.g., in setUp() method)
 * that should run before every single test.
 */
abstract class TestCase extends BaseTestCase
{
    //
}
