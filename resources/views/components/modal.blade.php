{{-- 
    Modal Component — A reusable popup overlay with Alpine.js.
    
    Props:
    - name:  Unique identifier for this modal (e.g. 'create-idea', 'edit-idea')
    - title: Text shown at the top of the modal
    
    How it works:
    - Listens for 'open-modal' events on the window
    - Opens only if the event detail matches this modal's name
    - Closes on: pressing Escape, clicking outside, or clicking the X button
    - x-trap: Traps keyboard focus inside the modal when open (accessibility)
    - Has enter/leave transitions for smooth animation
    
    Usage to open: $dispatch('open-modal', 'modal-name')
    Usage to close: $dispatch('close-modal')
--}}
@props(['name', 'title'])

<div
    x-data="{ show: false, name: @js($name) }"
    x-show="show"
    x-trap="show"
    @open-modal.window="show = ($event.detail === name)"
    @close-modal="show = false"
    @keydown.escape.window="show = false"
    x-transition:enter="ease-out duration-250"
    x-transition:enter-start="opacity-0 -translate-y-4"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-250"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 -translate-y-4"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs"
    style="display:none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-{{ $name }}-title"
    :aria-hidden="!show"
    tabindex="-1"
    id="modal-{{ $name }}"

>
    {{-- Modal body — closes when clicking outside (@click.away) --}}
    <x-card @click.away="show = false" class="shadow-xl max-w-2xl w-full max-h-[80vh] overflow-auto">
        {{-- Header: title + close button --}}
        <div class="flex justify-between items-center">
            <h2 id="modal-{{ $name }}-title" class="text-2xl font-bold">{{ $title }}</h2>

            <button @click="show = false" aria-label="Close modal">
                <x-icons.close />
            </button>
        </div>

        {{-- Modal content (injected via {{ $slot }}) --}}
        <div class="mt-6">
            {{ $slot }}
        </div>
    </x-card>
</div>