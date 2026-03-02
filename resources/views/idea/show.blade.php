<x-layout>
    <div class="py-8 md:py-12 max-w-4xl mx-auto">
        <div class="flex justify-between items-center">
            <a href="{{ route('idea.index') }}" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back />
                Back to Ideas
            </a>
            <div class="gap-x-3 flex items-center">
                <button class="btn btn-outlined">
                    <x-icons.external />
                    Edit Idea
                </button>

                <form action="{{ route('idea.destroy', $idea) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outlined text-red-400">Delete</button>
                </form>
            </div>

        </div>
        <div class="mt-8 space-y-6">
            <h1 class="text-3xl font-bold">{{ $idea->title }}</h1>

            <div class="mt-5 flex justify-end items-center space-x-3 text-sm text-muted-foreground">                     
                <div> {{ $idea->created_at->diffForHumans() }} </div>
                <div class="">
                    <x-idea.status-label :status=" $idea->status->value">
                        {{ $idea->status->label() }}
                    </x-idea.status-label>
                </div>                
            </div>

            <x-card class="mt-4">
                <div class="text-foreground max-w-none cursor-pointer"> {{ $idea->description }} </div>
            </x-card>

            @if ($idea->links->count())
                <div class="mt-6">
                    <h3 class="font-bold text-xl mt-6">Links</h3>

                    <div class="mt-2 space-y-1">
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
        
    </div>
</x-layout>