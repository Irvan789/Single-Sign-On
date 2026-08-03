<label class="inline-flex cursor-pointer items-center gap-1.5 text-sm/4 font-medium text-[#4a3f35]">
    <input
        type="checkbox"
        class="size-4 accent-[#4a3f35]"
        {{ $attributes }}
    />

    {{ $slot }}
</label>
