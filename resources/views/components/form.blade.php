<form {{ $attributes->merge(['autocomplete' => 'off'])->twMerge('flex-1 space-y-4') }}>
    {{ $slot }}
</form>
