<?php

declare(strict_types=1);

namespace App\Models;

use App\IdeaStatus;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Idea Model — The main entity of this application.
 *
 * An "Idea" is something a user wants to track. It has:
 * - a title and optional description
 * - a status (pending, in_progress, completed)
 * - optional links (stored as JSON array)
 * - an optional image
 * - multiple actionable steps (see Step model)
 *
 * Each idea belongs to one User, and one User can have many Ideas.
 */
class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory;

    /**
     * Attribute casting — tells Laravel how to convert database values.
     *
     * 'links' is stored as JSON in the database but used as an ArrayObject in PHP.
     * 'status' is stored as a string but automatically converted to the IdeaStatus enum.
     */
    protected $casts = [
        'links' => AsArrayObject::class,
        'status' => IdeaStatus::class,
    ];

    /**
     * Default values for new ideas.
     *
     * When creating a new Idea without specifying a status,
     * it will automatically be set to "pending".
     */
    protected $attributes = [
        'status' => IdeaStatus::PENDING->value,
    ];

    /**
     * Count how many ideas the user has in each status.
     *
     * Returns something like:
     *   ['pending' => 3, 'in_progress' => 2, 'completed' => 5, 'all' => 10]
     *
     * This is used on the index page to show filter badge counts.
     */
    public static function statusCounts(User $user): Collection
    {
        // Group the user's ideas by status and count each group
        $counts = $user
            ->ideas()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Build a collection with all statuses (even those with 0 ideas)
        // and add an 'all' key with the total count
        return collect(IdeaStatus::cases())
            ->mapWithKeys(fn ($status) => [
                $status->value => $counts->get($status->value, 0),
            ])
            ->put('all', $user->ideas()->count());
    }

    /**
     * Relationship: An idea belongs to one user (the creator).
     *
     * Usage: $idea->user returns the User who created this idea.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An idea has many steps (actionable tasks).
     *
     * Usage: $idea->steps returns all Step records for this idea.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function formattedDescription(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => str($attributes['description'])->markdown());
    }
}
