<?php

declare(strict_types=1);

namespace App;

/**
 * IdeaStatus Enum — Represents the possible states of an Idea.
 *
 * This is a "backed enum" (backed by string values) so it can be
 * stored directly in the database as plain text.
 *
 * The three statuses are:
 * - PENDING:     The idea is just captured, not started yet
 * - IN_PROGRESS: The idea is currently being worked on
 * - COMPLETED:   The idea has been fully realized
 */
enum IdeaStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    /**
     * Get a human-friendly label for this status.
     *
     * Example: IdeaStatus::IN_PROGRESS->label() returns "In Progress"
     * Used in Blade views to display a nice name instead of "in_progress".
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }

    /**
     * Get all possible status values as a plain array of strings.
     *
     * Returns: ['pending', 'in_progress', 'completed']
     * Useful for validation rules and filtering.
     */
    public static function values(): array
    {
        return array_map(fn (IdeaStatus $status) => $status->value, self::cases());
    }
}
