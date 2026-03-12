<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;

/**
 * StepController — Handles toggling a step's completed status.
 *
 * Steps are sub-tasks of an Idea. This controller only uses the update method
 * to toggle a step between completed and not completed (checkbox behavior).
 */
class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        //
    }

    /**
     * Toggle a step's completed status.
     *
     * If the step is currently NOT completed, mark it as completed.
     * If the step IS completed, mark it as not completed.
     *
     * The "!" (not) operator flips the boolean:
     *   false becomes true, true becomes false.
     *
     * After toggling, redirects back to the idea's show page.
     */
    public function update(Step $step)
    {
        $step->update(['completed' => ! $step->completed]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
