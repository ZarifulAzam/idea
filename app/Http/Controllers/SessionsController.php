<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * SessionsController — Handles user login and logout (authentication sessions).
 *
 * A "session" represents a logged-in user. This controller:
 * - Shows the login form
 * - Authenticates the user with email + password
 * - Logs the user out and clears their session
 */
class SessionsController extends Controller
{
    /**
     * Show the login form.
     *
     * Only accessible to guests (not already logged-in users).
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Attempt to log the user in.
     *
     * Steps:
     * 1. Validate that email and password are provided
     * 2. Try to match credentials against the database (Auth::attempt)
     * 3. If credentials are wrong, send back with an error message
     * 4. If successful, regenerate the session ID (prevents session fixation attacks)
     * 5. Redirect to the intended page (or ideas list by default)
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:3', 'max:255'],
        ]);

        // Auth::attempt checks if email+password match a user in the database
        if (! Auth::attempt($attributes)) {
            return back()->withErrors(
                [
                    'password' => 'Invalid credentials',

                ])->withInput();
        }

        // Regenerate session ID to prevent session fixation attacks
        $request->session()->regenerate();

        // redirect()->intended() sends the user where they originally wanted to go
        // (e.g., if they tried to visit /ideas but got redirected to /login)
        return redirect()->intended(route('idea.index'))->with('success', 'Login successful!!');
    }

    /**
     * Log the user out.
     *
     * Steps:
     * 1. Log out the user (clear authentication)
     * 2. Invalidate the session (destroy all session data)
     * 3. Regenerate the CSRF token (security best practice)
     * 4. Redirect to the homepage
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
