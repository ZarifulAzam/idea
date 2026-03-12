<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Step Model — An actionable task that belongs to an Idea.
 *
 * Each step has:
 * - a description (what needs to be done)
 * - a completed flag (true/false — is this step done?)
 *
 * Steps help users break down their ideas into smaller, manageable tasks.
 * Each step belongs to exactly one Idea.
 */
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    /**
     * Attribute casting.
     *
     * 'completed' is stored as 0/1 in the database
     * but automatically converted to true/false in PHP.
     */
    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * Relationship: A step belongs to one idea.
     *
     * Usage: $step->idea returns the Idea this step is part of.
     */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
