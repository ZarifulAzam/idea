<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-background text-foreground">
    <x-layout.nav />

    {{-- Flash Data --}}
    @session('success')
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000ms)"
            x-show="show"
            x-transition.opacity.duration.300ms
            class="fixed bottom-4 right-4 bg-primary text-primary-foreground px-6 py-3 rounded-xl shadow-lg">
            {{ $value }}
        </div>
    @endsession

    <main class="max-w-7xl mx-auto px-6">
        {{ $slot }}
    </main>
</body>
</html>