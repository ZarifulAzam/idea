<x-layout>
    <x-form title="Log in" description="Glad to have you back">
        <form action="/login" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="email" label="Email" type="email" />
            <x-form.field name="password" label="Password" type="password" />
            
            <div class="flex justify-center">
                <button type="submit" class="btn mt-2 h-10 ">Create account</button>
            </div>
        </form>
    </x-form>
</x-layout>