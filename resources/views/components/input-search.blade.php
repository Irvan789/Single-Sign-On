<div class="relative flex-1 sm:flex-none">
    <input
        {{ $attributes->merge(['type'=>'text', 'autocomplete'=>'off', 'name'=>'search'])->twMerge('w-full rounded border-none bg-[#fefdfb] py-2 pr-9 pl-3 text-sm/5 font-normal text-[#685e50] inset-ring inset-ring-[#c9b896]/50 outline-none read-only:cursor-not-allowed read-only:bg-[#f4eee2]/70 focus:inset-ring-[#c9b896]/80 disabled:bg-[#f4eee2]/70"
        autocomplete="off') }}
    />

    <button
        type="submit"
        class="absolute inset-y-0 inset-e-0 size-9 p-2"
    >
        <span class="icon-[tabler--search] size-4 text-[#544636]"> </span>
    </button>
</div>
