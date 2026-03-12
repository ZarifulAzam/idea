<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model — Represents a registered user of the application.
 *
 * Users can:
 * - Register and log in
 * - Create, view, update, and delete their own ideas
 * - Update their profile (name, email, password)
 *
 * Extends Authenticatable which provides login/session functionality.
 * Uses Notifiable to allow sending notifications (like email).
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Fields that can be set using mass assignment (e.g. User::create([...])).
     *
     * Only these fields are allowed to be filled — this protects against
     * accidentally setting sensitive fields like 'is_admin'.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Fields hidden when the model is converted to JSON or array.
     *
     * Passwords and tokens should never be exposed in API responses.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define how certain database columns should be converted in PHP.
     *
     * - email_verified_at: stored as string, used as Carbon datetime object
     * - password: automatically hashed when set (no need to Hash::make() manually)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: A user has many ideas.
     *
     * Usage: $user->ideas returns all ideas created by this user.
     */
    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }
}
