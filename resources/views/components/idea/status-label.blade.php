{{-- 
    Status Label Component — A colored badge showing an idea's status.
    
    Props:
    - status: The status value string ('pending', 'in_progress', 'completed')
    
    Color scheme:
    - pending:     Yellow badge
    - in_progress: Blue badge
    - completed:   Green/primary badge
    
    Usage: 
      <x-idea.status-label status="pending">Pending</x-idea.status-label>
--}}
@props(['status' => 'pending'])

@php
    // Base classes for the badge
    $classes = 'inline-block px-2 py-1 rounded-full text-xs font-medium border';

    // Add color classes based on the status value
    if ($status === 'pending') {
        $classes .= ' bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
    }

    if ($status === 'in_progress') {
        $classes .= ' bg-blue-500/10 text-blue-500 border-blue-500/20';
    }

    if ($status === 'completed') {
        $classes .= ' bg-primary/10 text-primary border-primary/20';
    }

@endphp

<span {{ $attributes(['class' => $classes]) }}>
    {{ $slot }}
</span>
