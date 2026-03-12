{{-- 
    Idea Modal Component — The form for creating or editing an idea.
    
    This is a dual-purpose component:
    - When called WITHOUT an idea: acts as "Create Idea" modal
    - When called WITH an idea: acts as "Edit Idea" modal
    
    Props:
    - idea: An Idea model (defaults to a new empty Idea if not provided)
    
    The form uses Alpine.js (x-data) to handle dynamic fields:
    - status: Toggle buttons instead of a dropdown
    - steps: Add/remove steps dynamically without page reload
    - links: Add/remove links dynamically without page reload
    
    Usage:
      <x-idea.modal />              — Create mode (empty form)
      <x-idea.modal :idea="$idea" /> — Edit mode (pre-filled form)
--}}
@props(['idea' => new App\Models\Idea()])

{{-- 
    Choose modal name and title based on whether we're editing or creating.
    $idea->exists is true if the idea has been saved to the database.
--}}
<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit Idea' : 'Create Idea' }}">
    {{-- 
        Alpine.js reactive data for the form:
        - status: Currently selected status button
        - newLink/newStep: Temporary input values for adding new items
        - stepCounter: Ensures unique keys for dynamically added steps
        - steps: Array of step objects (loaded from existing idea or empty)
        - links: Array of URL strings (loaded from existing idea or empty)
        
        old() function preserves form values if validation fails.
    --}}
    <form 
        x-data="{
            status: @js(old('status', $idea->status->value)),
            newLink: '',
            newStep: '',
            stepCounter: 0,
            steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed']))).map((step, index) => ({
                ...step,
                clientId: step.id ?? `existing-${index}`,
            })),
            links: @js(old('links', $idea->links ?? [])),
        }" 
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}" method="POST" enctype="multipart/form-data">

        @csrf
        {{-- Use PATCH method for updates (HTML forms only support GET/POST) --}}
        @if($idea->exists)
            @method('PATCH')
        @endif
        <div class="space-y-6">
            {{-- Title Field --}}
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter an idea for your title"
                :value="$idea->title"
                autofocus
                required
            />

            {{-- Status Selection — Three toggle buttons instead of a dropdown --}}
            <div class="space-y-3">
                <label for="status" class="label">Status</label>

                <div class="flex gap-x-3">
                    {{-- Loop through all status options and create a button for each --}}
                    @foreach (App\IdeaStatus::cases() as $status)
                        {{-- @click sets the Alpine status variable; :class toggles the style --}}
                        <button type="button" @click="status = @js($status->value)" class="btn flex-1" :class="{'btn-outlined' : status !== @js($status->value)}">
                            {{ $status->label() }}
                        </button>
                    @endforeach

                    {{-- Hidden input sends the selected status value with the form --}}
                    <input type="hidden" name="status" :value="status">
                </div>

                <x-form.error name="status" />
            </div>

            {{-- Description Field (textarea) --}}
            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea"
                :value="$idea->description"
            />

            {{-- Featured Image Upload --}}
            <div class="space-y-2">
                <label for="image" class="label">Featured image</label>

                {{-- Show current image with remove button if one exists --}}
                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img class="w-full h-48 object-cover rounded-lg" src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}">

                        <div class="flex justify-end">
                            {{-- This button submits a separate form (below) to delete the image --}}
                            <button form="delete-image-form" class="btn btn-outlined text-red-400">Remove Image</button>
                        </div>
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/*">
                <x-form.error name="image" />
            </div>

            {{-- 
                Dynamic Steps Section — Add/remove steps using Alpine.js.
                
                Each step is an object: { clientId, description, completed }
                - clientId: Used as a unique key for Alpine's x-for loop
                - description: What needs to be done
                - completed: Whether the step is done (true/false)
                
                Steps are rendered as hidden form inputs so they're submitted with the form.
            --}}
            <div>
                <fieldset class="space-y-3">
                    <legend class="label">Actionable Steps</legend>

                    {{-- Loop through existing steps --}}
                    <template x-for="(step, index) in steps" :key="step.clientId">
                        <div class="flex gap-x-2 items-center">
                            {{-- Step description (read-only display) --}}
                            <input :name="`steps[${index}][description]`" x-model="step.description" class="input" readonly>
                            {{-- Hidden completed value --}}
                            <input type="hidden" :name="`steps[${index}][completed]`" :value="step.completed ? '1' : '0'">

                            {{-- Remove step button (X icon) --}}
                            <button 
                                type="button" 
                                aria-label="Remove step"
                                @click="steps.splice(index, 1)"
                            >
                                <x-icons.close />
                            </button>
                        </div>
                    </template>

                    {{-- Input field for adding a new step --}}
                    <div class="flex gap-x-2 items-center">
                        <input 
                            x-model="newStep"
                            id="new-step"
                            data-test="new-step"
                            placeholder="Whats need to be done to make this idea happen?"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        {{-- Add step button (+ icon, rotated X) --}}
                        {{-- Pushes a new step object into the array and clears the input --}}
                        <button 
                            type="button" 
                            @click="steps.push({
                                clientId: `new-${Date.now()}-${stepCounter++}`,
                                description: newStep.trim(),
                                completed: false,
                            }); 
                            newStep = '';"
                            :disabled="newStep.trim().length === 0"
                            aria-label="Add step button"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45" />
                        </button>
                    </div>
                </fieldset>
            </div>

            {{-- 
                Dynamic Links Section — Add/remove URLs using Alpine.js.
                
                Similar to steps, but simpler — each link is just a URL string.
            --}}
            <div>
                <fieldset class="space-y-3">
                    <legend class="label">Links</legend>

                    {{-- Loop through existing links --}}
                    <template x-for="(link, index) in links" :key="link">
                        <div class="flex gap-x-2 items-center">
                            <input name="links[]" x-model="link" class="input">

                            {{-- Remove link button --}}
                            <button 
                                type="button" 
                                aria-label="Remove link"
                                @click="links.splice(index, 1)"
                            >
                                <x-icons.close />
                            </button>
                        </div>
                    </template>

                    {{-- Input field for adding a new link --}}
                    <div class="flex gap-x-2 items-center">
                        <input 
                            x-model="newLink"
                            type="url"
                            id="new-link"
                            placeholder="http://example.com"
                            autocomplete="url"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        {{-- Add link button --}}
                        <button 
                            type="button" 
                            @click="links.push(newLink.trim()); 
                            newLink = '';"
                            :disabled="newLink.trim().length === 0"
                            aria-label="Add link button"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45" />
                        </button>
                    </div>
                </fieldset>
            </div>
        </div>
            
        {{-- Form Actions: Cancel and Submit buttons --}}
        <div class="flex justify-end gap-x-5 mt-6">
            <button type="button" @click="$dispatch('close-modal')" class="nav-link">Cancel</button>
            <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
        </div>
    </form>

    {{-- Separate form for deleting the image (submitted by the "Remove Image" button above) --}}
    @if ($idea->image_path)
        <form action="{{ route('idea.image.destroy', $idea) }}" method="POST" id="delete-image-form">
            @csrf
            @method('DELETE')
        </form>
    @endif

    
</x-modal>