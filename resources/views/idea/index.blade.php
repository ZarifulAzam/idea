{{-- 
    Ideas Index Page — The main dashboard showing all the user's ideas.
    
    This page has three sections:
    1. Header — with a "What's the idea" button that opens the create modal
    2. Status filter tabs — All, Pending, In Progress, Completed (with counts)
    3. Ideas grid — Cards showing each idea with title, description, date, status
    
    Variables passed from IdeaController@index:
    - $ideas: Collection of the user's Idea models (filtered by status if applicable)
    - $statusCounts: Collection with counts per status + 'all' total
--}}
<x-layout>
    <div>
        {{-- Page Header --}}
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold tracking-tight">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan</p>

            {{-- Clickable card that opens the "Create Idea" modal --}}
            {{-- x-data makes this an Alpine.js component --}}
            {{-- @click dispatches an event that the modal component listens for --}}
            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')" 
                is="button" 
                type="button"
                class="mt-8 cursor-pointer h-32 w-full text-left">

                <p>What's the idea</p>
            </x-card>
        </header>

        {{-- Status Filter Tabs — click to filter ideas by status --}}
        <div class="flex flex-wrap gap-2">
            {{-- "All" tab — no status filter, shows all ideas --}}
            <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}">All <span class="text-xs pl-3">{{ $statusCounts->get('all') }}</span></a>

            {{-- One tab per status (Pending, In Progress, Completed) --}}
            @foreach (App\IdeaStatus::cases() as $status)
                <a href="{{ route('idea.index', ['status' => $status->value]) }}" class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }}
                    <span class="text-xs pl-3">{{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        {{-- Ideas Grid — displays idea cards in a 2-column grid on medium+ screens --}}
        <div class="mt-8 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Loop through ideas; show empty message if none exist --}}
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">

                        {{-- Show featured image at top of card if one exists --}}
                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-lg overflow-hidden">
                                <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>

                        {{-- Truncate description to 3 lines with CSS (line-clamp-3) --}}
                        <div class="mt-4 line-clamp-3">{{ $idea->description }}</div>

                        {{-- Footer: date and status badge --}}
                        <div class="grid grid-cols-2 mt-4">
                            <div class="mt-3">{{ $idea->created_at->diffForHumans() }}</div>
                            <div class="mt-3 text-right">
                                <x-idea.status-label status="{{ $idea->status }}">
                                    {{ $idea->status->label() }}
                                </x-idea.status-label>
                            </div>
                        </div>
                    </x-card>

                @empty
                    <x-card>
                        <p>No idea at this time</p>
                    </x-card>
                @endforelse
            </div>
        </div>

        {{-- Create Idea Modal — shown when the user clicks "What's the idea" --}}
        <x-idea.modal />
    </div>
</x-layout>