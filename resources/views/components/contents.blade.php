<div {{ $attributes->only(['class', 'x-data'])->twMerge('mx-auto flex w-full max-w-2xl flex-col gap-3.5') }}>
    {{ $slot }}
</div>
