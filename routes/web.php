<?php

/**
 * Web Routes — Defines all the URLs and pages in the application.
 *
 * Each route maps a URL + HTTP method to a controller action.
 * Middleware controls access:
 * - 'auth'  = must be logged in to access
 * - 'guest' = must NOT be logged in (for login/register pages)
 *
 * Route naming (->name('...')) lets us reference routes by name
 * instead of hardcoding URLs. Example: route('idea.index') = /ideas
 */

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

// --- Homepage: redirect to ideas list ---
Route::redirect('/', 'ideas');

// --- Idea Routes (require login) ---
Route::get('/ideas', [IdeaController::class, 'index'])->name('idea.index')->middleware('auth');           // List all ideas
Route::post('/ideas', [IdeaController::class, 'store'])->name('idea.store')->middleware('auth');          // Create a new idea
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show')->middleware('auth');      // View one idea
Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])->name('idea.update')->middleware('auth'); // Update an idea
Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('idea.destroy')->middleware('auth'); // Delete an idea

// --- Idea Image Route (require login) ---
Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])->name('idea.image.destroy')->middleware('auth'); // Remove idea image

// --- Step Route (require login) ---
Route::patch('/steps/{step}', [StepController::class, 'update'])->name('step.update')->middleware('auth'); // Toggle step completed

// --- Registration Routes (guests only) ---
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register')->middleware('guest');       // Show register form
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store')->middleware('guest'); // Process registration

// --- Login Routes (guests only) ---
Route::get('/login', [SessionsController::class, 'create'])->name('login')->middleware('guest');       // Show login form
Route::post('/login', [SessionsController::class, 'store'])->name('login.store')->middleware('guest'); // Process login

// --- Logout Route (require login) ---
Route::post('/logout', [SessionsController::class, 'destroy'])->name('logout')->middleware('auth'); // Log out

// --- Profile Routes (require login) ---
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');     // Show profile form
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth'); // Update profile
