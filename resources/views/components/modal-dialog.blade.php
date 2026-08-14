<div
    {{ $attributes->only(['x-show']) }}
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-s-0 top-0 z-50 flex size-full bg-[#f4eee2]/80 px-4 py-8 transition-opacity"
>
    <div
        class="relative m-auto grid w-full max-w-md grid-rows-[max-content_auto_max-content] rounded-xs bg-[#fbf8f1] p-5 inset-ring inset-ring-[#c9b896]/50 sm:max-w-116"
        {{ $attributes->only('x-on:click.outside') }}
    >
        <div class="space-y-0.5">
            <h1 class="h-5 font-serif text-2xl/5 font-medium text-[#4a3f35]">{{ $title }}</h1>

            <p class="text-[0.938rem]/4.75 font-medium text-[#8a7f70]">{{ $description }}</p>
        </div>

        {{ $slot }}
    </div>
</div>
