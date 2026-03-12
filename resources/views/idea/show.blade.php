{{-- 
    Idea Detail Page — Shows all information about a single idea.
    
    Sections:
    1. Navigation bar — Back button + Edit/Delete buttons
    2. Featured image (if one exists)
    3. Title, date, and status badge
    4. Description card
    5. Actionable steps with toggleable checkboxes
    6. Links list
    7. Edit modal (hidden until the Edit button is clicked)
    
    Variable passed from IdeaController@show:
    - $idea: The Idea model with its steps and links
--}}
<x-layout>
    <div class="py-8 md:py-12 max-w-4xl mx-auto">
        {{-- Top Bar: Back button and action buttons --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('idea.index') }}" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back />
                Back to Ideas
            </a>
            <div class="gap-x-3 flex items-center">
                {{-- Edit button — dispatches Alpine event to open the edit modal --}}
                <button 
                    x-data 
                    class="btn btn-outlined"
                    @click="$dispatch('open-modal', 'edit-idea')"
                >
                    <x-icons.external />
                    Edit Idea
                </button>

                {{-- Delete button — submits a DELETE form to remove the idea --}}
                <form action="{{ route('idea.destroy', $idea) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outlined text-red-400">Delete</button>
                </form>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            {{-- Featured Image (only shown if the idea has an uploaded image) --}}
            @if ($idea->image_path)
                <div class="rounded-lg overflow-hidden">
                    <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}">
                </div>
            @endif

            {{-- Idea Title --}}
            <h1 class="text-3xl font-bold tracking-tight">{{ $idea->title }}</h1>

            {{-- Date and Status Badge --}}
            <div class="flex justify-end items-center space-x-3 text-sm text-muted-foreground">
                <div>{{ $idea->created_at->diffForHumans() }}</div>
                <div>
                    <x-idea.status-label :status="$idea->status->value">
                        {{ $idea->status->label() }}
                    </x-idea.status-label>
                </div>
            </div>

            {{-- Description (only shown if one exists) --}}
            @if ($idea->description)
                <x-card class="mt-6" is="div">
                    {{-- formattedDescription accessor converts markdown to HTML --}}
                    <div class="text-foreground max-w-none prose">{!! $idea->formattedDescription !!}</div>
                </x-card>
            @endif

            {{-- Actionable Steps — each step is a toggleable checkbox --}}
            @if ($idea->steps->count())
                <div>
                    <h3 class="font-bold text-xl">Actionable Steps</h3>

                    <div class="mt-3 space-y-1">
                        @foreach ($idea->steps as $step)
                            <x-card class="text-primary font-medium flex gap-x-3 items-center">
                                {{-- Each step has its own form that toggles completed on click --}}
                                <form action="{{ route('step.update', $step) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="flex items-center gap-x-3">
                                        {{-- Checkbox button: filled if completed, outlined if not --}}
                                        <button type="submit" role="checkbox" class="size-5 flex items-center justify-center rounded-lg text-primary-foreground {{ $step->completed ? 'bg-primary' : 'border border-primary' }}">&check;</button>
                                        {{-- Step text: crossed out if completed --}}
                                        <span class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}">{{ $step->description }}</span>
                                    </div>
                                </form>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
            
            {{-- Related Links — clickable list of external URLs --}}
            @if ($idea->links->count())
                <div>
                    <h3 class="font-bold text-xl">Links</h3>

                    <div class="mt-3 space-y-1">
                        @foreach ($idea->links as $link)
                            <x-card :href="$link" class="text-primary font-medium flex gap-x-3 items-center">
                                <x-icons.external class="w-4 h-4" />
                                {{ $link }}
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Edit Idea Modal — hidden until user clicks "Edit Idea" button --}}
        {{-- Passing :idea="$idea" pre-fills the modal form with existing data --}}
        <x-idea.modal :idea="$idea" />
    </div>
</x-layout>