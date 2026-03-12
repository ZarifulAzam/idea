<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Storage;

/**
 * IdeaImageController — Handles removing an idea's featured image.
 *
 * This is a separate controller because image removal is its own action,
 * independent from updating the idea's other fields.
 */
class IdeaImageController extends Controller
{
    /**
     * Remove the image from an idea.
     *
     * What this does:
     * 1. Delete the actual image file from the public storage disk
     * 2. Set the idea's image_path to null in the database
     * 3. Redirect back to the previous page with a success message
     */
    public function destroy(Idea $idea)
    {
        // Delete the physical file from storage/app/public/
        Storage::disk('public')->delete($idea->image_path);

        // Clear the path in the database
        $idea->update(['image_path' => null]);

        return back()->with('success', 'Image removed');
    }
}
