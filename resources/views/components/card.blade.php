<div
    {{ $attributes->only('class')->twMerge('flex flex-col gap-4 rounded-lg bg-[#fbf8f1] p-5 inset-ring inset-ring-[#c9b896]/40') }}
>
    <div class="block space-y-0.5">
        <div class="font-serif text-2xl/5">{{ $title }}</div>

        <div
            class="text-sm/4.5 {{ $attributes->has('variant') && $variant == 'danger' ? 'text-[#a8503d]/80' :'text-[#544636]/75' }}"
        >
            {{ $description }}
        </div>
    </div>

    {{ $slot }}
</div>
