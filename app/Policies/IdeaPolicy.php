<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

/**
 * IdeaPolicy — Controls WHO is allowed to do WHAT with Ideas.
 *
 * Policies are Laravel's way of handling authorization.
 * Instead of checking permissions inside controllers,
 * we define rules here and call Gate::authorize() in controllers.
 *
 * Laravel automatically discovers this policy because it follows
 * the naming convention: IdeaPolicy for the Idea model.
 */
class IdeaPolicy
{
    /**
     * Check if the user is the owner of this idea.
     *
     * This single method is used to guard show, update, and delete.
     * Only the user who created the idea can view, edit, or delete it.
     *
     * How it works:
     * - $idea->user gets the User who owns this idea
     * - ->is($user) checks if that's the same person making the request
     * - Returns true (allowed) or false (403 Forbidden)
     */
    public function workWith(User $user, Idea $idea)
    {
        return $idea->user->is($user);
    }
}
