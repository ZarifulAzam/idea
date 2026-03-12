<?php

/**
 * Migration: Create the 'steps' table.
 *
 * Steps are actionable sub-tasks that belong to an Idea.
 * Example: If the idea is "Build a website", steps might be:
 *   - "Design the homepage"
 *   - "Set up hosting"
 *   - "Write content"
 */

use App\Models\Idea;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the 'steps' table with these columns:
     *
     * - id:          Auto-incrementing primary key
     * - idea_id:     Foreign key linking to the ideas table (which idea this step belongs to)
     *                cascadeOnDelete = if the idea is deleted, its steps are also deleted
     * - description: What needs to be done (required, max 255 characters)
     * - completed:   Whether this step is done (true/false)
     * - timestamps:  Automatically adds created_at and updated_at columns
     */
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Idea::class)->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->boolean('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration — drop the 'steps' table.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
