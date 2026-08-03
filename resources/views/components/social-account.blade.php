<div
    class="flex flex-row items-center justify-between gap-4 rounded bg-[#f4eee2]/40 p-4 inset-ring inset-ring-[#c9b896]/40"
>
    <div class="flex flex-col">
        <div class="text-base/5 font-medium">{{ $name }}</div>

        <div class="text-xs/3.5 break-all text-[#8a7f70]">{{ $email }}</div>
    </div>

    <a
        {{ $attributes->merge(['href' => $url ?? null])->twMerge(['min-w-15 rounded px-2.5 py-2 text-center text-xs/3 capitalize transition-colors duration-300', $email != 'Not Linked' ? 'text-[#a8503d] inset-ring inset-ring-[#a8503d]/40 hover:bg-[#a8503d]/10' : 'inset-ring inset-ring-[#c9b896]/60 hover:bg-[#c9b896]/10']) }}
    >
        {{ $slot }}
    </a>
</div>
