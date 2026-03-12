<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Pest is the testing framework for this application.
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
| Each test file uses its own uses() call to bind TestCase and RefreshDatabase
| (this gives IDE support and makes bindings explicit per file).
|
*/

// Per-file uses() declarations provide IDE support and make bindings explicit.

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| Custom expectations extend Pest's assertion library.
| expect()->extend() lets you create reusable assertions.
|
| Example: expect($value)->toBeOne() checks that $value === 1
|
*/

expect()->extend('toBeOne', fn () => $this->toBe(1));

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Global helper functions available in all test files.
| Define shared test utilities here to reduce code duplication.
|
*/

function something(): void
{
    // ..
}
