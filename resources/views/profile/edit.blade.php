{{-- 
    Profile Edit Page — Allows the logged-in user to update their info.
    
    Form submits to PATCH /profile (handled by ProfileController@update).
    @method('PATCH') is needed because HTML forms only support GET and POST,
    so Laravel uses a hidden field to simulate PATCH requests.
    
    :value="$user->name" pre-fills the input with the current user's data.
    Password field is left empty — only updated if the user types a new one.
--}}
<x-layout>
    <x-form title="Edit Profile" description="Update your profile information">
        <form action="/profile" method="POST" class="mt-10 space-y-4">
            @csrf
            @method('PATCH')

            <x-form.field name="name" label="Name" :value="$user->name" />
            <x-form.field name="email" label="Email" type="email" :value="$user->email" />
            <x-form.field name="password" label="Password" type="password" />
            
            <div class="flex justify-center mt-2">
                <button type="submit" class="btn w-full">Update Profile</button>
            </div>
        </form>
    </x-form>
</x-layout>