<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
         $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:3', 'max:255'],
        ]);

        if (!Auth::attempt($attributes)) {
            return back()->withErrors(
                [
                    'password' => 'Invalid credentials',

                ])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->intended('/')->with('success', 'Login successful!!');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
