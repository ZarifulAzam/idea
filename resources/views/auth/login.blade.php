{{-- 
    Login Page — Allows existing users to sign in.
    
    Form submits to POST /login (handled by SessionsController@store).
    @csrf generates a security token.
    
    If login fails, validation errors are shown via <x-form.field> error display.
--}}
<x-layout>
    <x-form title="Log in" description="Glad to have you back">
        <form action="/login" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="email" label="Email" type="email" />
            <x-form.field name="password" label="Password" type="password" />
            
            <div class="flex justify-center mt-2">
                <button type="submit" class="btn w-full">Log in</button>
            </div>
        </form>
    </x-form>
</x-layout>