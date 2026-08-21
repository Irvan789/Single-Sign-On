<label
    class="relative flex flex-col gap-1.25 text-sm/4 font-medium text-[#4a3f35]"
    x-data="{ showPassword: false }"
    {{ $attributes->only(['x-show']) }}
>
    {{ $label ?? '' }}

    @if ($type != 'password')
        <input
            type="{{ $type ?? 'text' }}"
            {{ $attributes->except(['label'])->merge(['autocomplete' => 'off'])->twMerge('rounded border-none bg-[#fefdfb] px-3 py-2 text-sm/5 font-normal text-[#685e50] inset-ring inset-ring-[#c9b896]/50 outline-none read-only:cursor-not-allowed read-only:bg-[#f4eee2]/70 focus:inset-ring-[#c9b896]/80 disabled:bg-[#f4eee2]/70') }}
        />
    @else
        <input
            :type="showPassword ? 'text' : 'password'"
            {{ $attributes->except(['label'])->merge(['autocomplete' => 'off'])->twMerge('rounded border-none bg-[#fefdfb] px-3 py-2 text-sm/5 font-normal text-[#685e50] inset-ring inset-ring-[#c9b896]/50 outline-none read-only:cursor-not-allowed read-only:bg-[#f4eee2]/70 focus:inset-ring-[#c9b896]/80 disabled:bg-[#f4eee2]/70') }}
        />

        <button
            type="button"
            class="absolute inset-e-0 top-5.25 flex shrink-0 cursor-pointer p-2.5"
            x-on:click="showPassword = !showPassword"
        >
            <span
                class="size-4 text-[#544636]"
                :class="showPassword
                    ? 'icon-[heroicons--eye-20-solid]'
                    : 'icon-[heroicons--eye-slash-20-solid]'"
            >
            </span>
        </button>
    @endif

    {{ $slot }}
</label>
