<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * ProfileController — Handles viewing and updating the user's profile.
 *
 * Allows the logged-in user to:
 * - View their current profile information
 * - Update their name, email, and optionally their password
 */
class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     *
     * Passes the current user's data to the view so the form
     * can be pre-filled with their existing name and email.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * Validation:
     * - name: required, max 255 characters
     * - email: required, valid email, unique (but the user's own email is allowed)
     *
     * Password handling:
     * - If a new password is provided, it gets hashed and saved
     * - If no password is provided, the existing password is kept unchanged
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                // unique but ignore the current user's own email
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                // Only hash and update password if one was provided
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]
        );

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
