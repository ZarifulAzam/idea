{{-- 
    Form Field Component — A reusable input or textarea with label and error.
    
    Props:
    - label: Text shown above the input (optional — no label if not provided)
    - name:  The field name (used for name="", id="", and error display)
    - type:  Input type: 'text' (default), 'email', 'password', 'textarea'
    - value: Pre-filled value (defaults to empty string)
    
    Features:
    - Automatically shows the old submitted value after validation failure (via old())
    - Displays validation errors below the field via <x-form.error>
    - Passes extra attributes (placeholder, required, etc.) through to the input
    
    Usage:
      <x-form.field name="email" label="Email" type="email" />
      <x-form.field name="description" type="textarea" :value="$idea->description" />
--}}
@props(['label' => false, 'name', 'type' => 'text', 'value' => '' ])

<div class="space-y-2">
    {{-- Label (only shown if provided) --}}
    @if ($label)
        <label for="{{ $name }}" class="label">{{ $label }}</label>
    @endif

    {{-- Render textarea or input based on type --}}
    @if ($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="textarea" 
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>
        
    @else
        <input 
            type="{{ $type }}" 
            class="input" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}" {{ $attributes }}
        >
    @endif

    {{-- Validation error message (shown if this field has errors) --}}
    <x-form.error name="{{ $name }}" />
</div>