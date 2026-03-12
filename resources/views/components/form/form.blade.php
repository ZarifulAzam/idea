{{-- 
    Form Wrapper Component — Centers a form with a title and description.
    
    Used for auth pages (login, register) and profile edit.
    Creates a centered card with a heading above the form fields.
    
    Props:
    - title: Main heading text (e.g. "Register an account")
    - description: Subtitle text (e.g. "Start tracking your ideas today")
    
    Usage:
      <x-form title="My Title" description="My subtitle">
          <form>...fields...</form>
      </x-form>
--}}
@props(['title', 'description'])

<div class="flex min-h-[calc(100dvh-4rem)] items-center justify-center px-4">
    <div class="w-full max-w-md">
        {{-- Centered heading --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight">{{ $title }}</h1>
            <p class="text-muted-foreground mt-1">{{ $description }}</p>
        </div>

        {{-- Form content injected here --}}
        {{ $slot }}
    </div>
</div>