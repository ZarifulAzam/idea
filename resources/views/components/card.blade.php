{{-- 
    Card Component — A reusable bordered container.
    
    Smart tag selection:
    - If 'is' prop is provided (e.g. is="button"), uses that tag
    - If 'href' attribute is present, renders as <a> (link)
    - Otherwise, renders as <div>
    
    Usage:
      <x-card>content</x-card>                    → renders as <div>
      <x-card href="/link">click me</x-card>      → renders as <a>
      <x-card is="button">click me</x-card>       → renders as <button>
--}}
@props(['is' => 'a'])


<{{ $is }} {{ $attributes(['class' => 'border border-border rounded-lg bg-card p-4 text-sm block']) }}>
    {{ $slot }}
</{{ $is }}>