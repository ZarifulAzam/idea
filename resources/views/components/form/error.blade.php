{{-- 
    Error Message Component — Shows a validation error for a specific field.
    
    Props:
    - name: The field name to check for errors
    
    Uses Laravel's @error directive which checks if validation failed for this field.
    If there's an error, shows the message in red text below the input.
    
    Usage: <x-form.error name="email" />
    Output (if error exists): <p class="text-red-600...">The email is required.</p>
--}}
@props(['name'])

@error($name)
    <p class="text-red-600 text-sm mt-1"> {{ $message }} </p>
@enderror