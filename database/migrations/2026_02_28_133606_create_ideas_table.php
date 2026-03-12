<?php

/**
 * Migration: Create the 'ideas' table.
 *
 * Migrations define the structure of database tables.
 * Run with: php artisan migrate
 * Undo with: php artisan migrate:rollback
 *
 * This creates the main table for storing user ideas.
 */

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the 'ideas' table with these columns:
     *
     * - id:          Auto-incrementing primary key (1, 2, 3...)
     * - user_id:     Foreign key linking to the users table (who owns this idea)
     *                cascadeOnDelete = if the user is deleted, their ideas are also deleted
     * - title:       The idea's title (required, max 255 characters)
     * - description: Longer text describing the idea (optional)
     * - status:      Current status: 'pending', 'in_progress', or 'completed' (defaults to 'pending')
     * - image_path:  Path to an uploaded image file (optional)
     * - links:       JSON array of related URLs (defaults to empty array [])
     * - timestamps:  Automatically adds created_at and updated_at columns
     */
    public function up(): void
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->string('image_path')->nullable();
            $table->json('links')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration — drop the 'ideas' table.
     *
     * Called when running: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
