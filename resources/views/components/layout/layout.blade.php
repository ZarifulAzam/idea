{{-- 
    Main Layout Component — The base HTML wrapper for every page.
    
    Usage: <x-layout> ... page content ... </x-layout>
    
    Structure:
    - <head>: Loads CSS and JS via Vite (Laravel's asset bundler)
    - <body>: Dark themed with the navigation bar + flash messages + content
    - Flash messages: Auto-dismiss after 3 seconds using Alpine.js
    - {{ $slot }}: Where each page's unique content is injected
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- @vite loads the CSS and JS files through Vite's build system --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    {{-- Navigation bar (logo, profile link, login/register links) --}}
    <x-layout.nav />

    {{-- 
        Flash Message Toast — Shows a success notification at the bottom right.
        
        Uses Alpine.js for:
        - x-data: tracks whether to show/hide the toast
        - x-init: auto-hide after 3 seconds
        - x-show + x-transition: fade in/out animation
        - x-cloak: prevents flash of content before Alpine initializes
        - @click: dismiss on click
    --}}
    @session('success')
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition.opacity.duration.300ms
            @click="show = false"
            x-cloak
            class="fixed bottom-4 right-4 bg-primary text-primary-foreground px-6 py-3 rounded-xl shadow-lg cursor-pointer">   
            {{ $value }}
        </div>
    @endsession

    {{-- Main content area — each page's content goes here via {{ $slot }} --}}
    <main class="max-w-7xl mx-auto px-6">
        {{ $slot }}
    </main>
</body>
</html>