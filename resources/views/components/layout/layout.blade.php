<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    <x-layout.nav />

    {{-- Flash Data --}}
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

    <main class="max-w-7xl mx-auto px-6">
        {{ $slot }}
    </main>
</body>
</html>