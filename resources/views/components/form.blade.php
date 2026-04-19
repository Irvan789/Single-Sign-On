<form {{ $attributes->merge(['autocomplete' => 'off'])->twMerge('flex flex-col gap-4') }}>
  {{ $slot }}
</form>
