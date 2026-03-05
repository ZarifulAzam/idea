@props(['name'])

@error('status')
    <p class="text-red-600 text-sm mt-1"> {{ $message }} </p>
@enderror