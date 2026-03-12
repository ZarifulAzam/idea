<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * RegisteredUserController — Handles new user registration.
 *
 * Allows guests to:
 * - View the registration form
 * - Create a new account
 *
 * After registration, the user is automatically logged in
 * and redirected to their ideas page.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the registration form.
     *
     * Only accessible to guests (not logged-in users).
     * The 'guest' middleware in web.php enforces this.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Create a new user account.
     *
     * Steps:
     * 1. Validate the form data (name, email must be unique, password)
     * 2. Create the user record in the database
     *    (password is automatically hashed because the User model has 'password' => 'hashed' cast)
     * 3. Log the new user in immediately
     * 4. Redirect to the ideas page with a welcome message
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:3', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Automatically log in the new user (no separate login step needed)
        Auth::login($user);

        return to_route('idea.index')->with('success', 'Registration complete!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
