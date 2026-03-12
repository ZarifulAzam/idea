{{-- 
    Registration Page — Allows new users to create an account.
    
    Uses the <x-layout> wrapper for consistent page structure.
    Uses <x-form> component for centered form styling.
    Uses <x-form.field> components for each input field.
    
    Form submits to POST /register (handled by RegisteredUserController@store).
    @csrf generates a hidden token to prevent cross-site request forgery attacks.
--}}
<x-layout>
    <x-form title="Register an account" description="Start tracking your ideas today">
        <form action="/register" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="name" label="Name" />
            <x-form.field name="email" label="Email" type="email" />
            <x-form.field name="password" label="Password" type="password" />
            
            <div class="flex justify-center mt-2">
                <button type="submit" class="btn w-full">Create account</button>
            </div>
        </form>
    </x-form>
</x-layout>