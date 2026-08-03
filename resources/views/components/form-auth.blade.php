<form
    {{ $attributes->except(['title', 'description', 'x-text'])->merge(['autocomplete' => 'off'])->twMerge('flex flex-col gap-4') }}
>
    <div class="space-y-0.5">
        <h1 class="h-5 font-serif text-2xl/5 font-medium text-[#4a3f35]">{{ $title }}</h1>

        @if ($attributes->has('description') || $attributes->has('x-text'))
            <p
                class="text-[0.938rem]/4.75 font-medium text-[#8a7f70]"
                @if ($attributes->has('x-text')) {{ $attributes->only('x-text') }} @endif
            >
                {{ $attributes->get('description') }}
            </p>
        @endif
    </div>

    {{ $slot }}
</form>
