<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\IdeaStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * IdeaRequest — Validates data when creating or updating an Idea.
 *
 * Form Requests are a clean way to handle validation outside the controller.
 * Laravel automatically runs these rules before the controller method executes.
 * If validation fails, the user is redirected back with error messages.
 *
 * This single request is shared by both store (create) and update actions.
 */
class IdeaRequest extends FormRequest
{
    /**
     * Is the user allowed to make this request?
     *
     * Returns true = anyone who is authenticated can submit this form.
     * (Authorization for specific ideas is handled in IdeaPolicy instead.)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for idea form data.
     *
     * These rules define what data is accepted:
     * - title:              Required, must be a string, max 255 characters
     * - description:        Optional text field
     * - status:             Required, must be one of the IdeaStatus enum values
     * - links:              Optional array of URLs
     * - links.*:            Each link must be a valid URL
     * - steps:              Optional array of step objects
     * - steps.*.description: Each step's description, max 255 characters
     * - steps.*.completed:  Each step's done/not-done flag (true/false)
     * - image:              Optional, must be an image file if provided
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'links' => ['nullable', 'array'],
            'links.*' => ['url'],
            'steps' => ['nullable', 'array'],
            'steps.*.description' => ['string', 'max:255'],
            'steps.*.completed' => ['boolean'],
            'image' => ['nullable', 'image'],
        ];
    }
}
