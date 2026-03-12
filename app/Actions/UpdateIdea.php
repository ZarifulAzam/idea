<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;

/**
 * UpdateIdea Action — Handles the logic for updating an existing idea.
 *
 * Similar to CreateIdea, but works on an existing Idea record.
 * Steps are replaced entirely (old ones deleted, new ones created).
 */
class UpdateIdea
{
    /**
     * Update an existing idea with the given attributes.
     *
     * What this does step by step:
     * 1. Pick only the safe fields (title, description, status, links)
     * 2. If a new image was uploaded, store it and update the path
     * 3. Wrap everything in a database transaction for safety
     * 4. Update the idea's fields
     * 5. Delete ALL old steps and create the new ones from scratch
     *    (this is a "replace all" strategy — simple and reliable)
     *
     * @param  array  $attributes  Validated form data from IdeaRequest
     * @param  Idea  $idea  The existing idea to update
     */
    public function handle(array $attributes, Idea $idea): void
    {
        // Only keep the fields we actually need
        $data = collect($attributes)->only(['title', 'description', 'status', 'links'])->toArray();

        // If a new image was uploaded, save it and update the path
        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('ideas', 'public');
        }

        // Transaction: update the idea and replace steps atomically
        DB::transaction(function () use ($data, $attributes, $idea) {
            // Update the idea's main fields
            $idea->update($data);

            // Remove all existing steps
            $idea->steps()->delete();

            // Re-create steps from the form data (fresh set)
            $idea->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
