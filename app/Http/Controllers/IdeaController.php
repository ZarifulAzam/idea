<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\Http\Requests\IdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * IdeaController — Handles all CRUD operations for Ideas.
 *
 * This is the main controller of the application.
 * It manages listing, creating, viewing, updating, and deleting ideas.
 *
 * All routes require authentication (enforced via 'auth' middleware in web.php).
 * Authorization ("is this MY idea?") is handled via Gate::authorize() + IdeaPolicy.
 */
class IdeaController extends Controller
{
    /**
     * Display the list of all ideas for the logged-in user.
     *
     * Features:
     * - Shows only the current user's ideas (not other users')
     * - Supports filtering by status via ?status=pending (query parameter)
     * - Shows status counts for the filter tabs (All, Pending, In Progress, Completed)
     * - Ideas are sorted newest first (latest())
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get ideas, optionally filtered by status if a valid status is in the URL
        $ideas = $user
            ->ideas()->when(in_array($request->input('status'), IdeaStatus::values()), fn ($query) => $query->where('status', $request->status))
            ->latest()->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'statusCounts' => Idea::statusCounts($user),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Not used — creation is handled via a modal on the index page.
     */
    public function create(): void
    {
        //
    }

    /**
     * Save a new idea to the database.
     *
     * How it works:
     * 1. IdeaRequest validates the form data automatically
     * 2. CreateIdea action handles the business logic (saving idea + steps + image)
     * 3. Redirects to the ideas list with a success message
     */
    public function store(IdeaRequest $request, CreateIdea $action)
    {
        $action->handle($request->safe()->all());

        return to_route('idea.index')->with('success', 'Idea created');
    }

    /**
     * Display a single idea's detail page.
     *
     * Gate::authorize ensures only the idea owner can view it.
     * If someone else tries, they get a 403 Forbidden response.
     */
    public function show(Idea $idea)
    {
        Gate::authorize('workWith', $idea);

        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Not used — editing is handled via a modal on the show page.
     */
    public function edit(Idea $idea): void
    {
        Gate::authorize('workWith', $idea);
    }

    /**
     * Update an existing idea in the database.
     *
     * How it works:
     * 1. Gate::authorize checks the user owns this idea
     * 2. IdeaRequest validates the form data
     * 3. UpdateIdea action handles the business logic (updating fields + replacing steps)
     * 4. Redirects back to the same page with a success message
     */
    public function update(IdeaRequest $request, Idea $idea, UpdateIdea $action)
    {
        Gate::authorize('workWith', $idea);

        $action->handle($request->safe()->all(), $idea);

        return back()->with('success', 'Idea updated');
    }

    /**
     * Delete an idea from the database.
     *
     * Gate::authorize ensures only the owner can delete their idea.
     * After deletion, the user is redirected back to the ideas list.
     */
    public function destroy(Idea $idea)
    {
        // authorize that this is allowed

        Gate::authorize('workWith', $idea);

        $idea->delete();

        return to_route('idea.index')->with('success', 'Idea deleted');
    }
}
