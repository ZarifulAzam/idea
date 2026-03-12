<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

/**
 * CreateIdea Action — Handles the logic for creating a new idea.
 *
 * Actions are single-purpose classes that encapsulate business logic.
 * This keeps controllers thin and logic reusable.
 */
class CreateIdea
{
    /**
     * The currently logged-in user is automatically injected
     * by Laravel's container using the #[CurrentUser] attribute.
     * This means we don't have to pass the user manually.
     */
    public function __construct(#[CurrentUser] protected User $user)
    {
        //
    }

    /**
     * Create a new idea with the given attributes.
     *
     * What this does step by step:
     * 1. Pick only the safe fields (title, description, status, links)
     * 2. If an image was uploaded, store it in the "ideas" folder on the public disk
     * 3. Wrap everything in a database transaction (if anything fails, nothing gets saved)
     * 4. Create the idea record linked to the current user
     * 5. Create all the associated steps (if any were provided)
     *
     * @param  array  $attributes  Validated form data from IdeaRequest
     */
    public function handle(array $attributes): void
    {
        // Only keep the fields we actually need (ignore extra data)
        $data = collect($attributes)->only(['title', 'description', 'status', 'links'])->toArray();

        // If the user uploaded an image, save it and store the file path
        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        // Use a transaction: both the idea AND its steps must save successfully,
        // or neither will be saved (prevents partial/broken data)
        DB::transaction(function () use ($data, $attributes) {
            $idea = $this->user->ideas()->create($data);

            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
