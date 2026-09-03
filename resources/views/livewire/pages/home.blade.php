<x-contents>
    <div class="flex flex-col items-center gap-4 py-1">
        <img
            src="{{ $user->avatar }}?size=128&r=g&d=mp"
            class="size-28 rounded-full border-2 border-[#c9b896]/25 lg:size-28"
        />

        <div class="relative z-10 min-w-0 flex-1 text-center">
            <h2 class="mb-1 font-serif text-[1.75rem]/4">Welcome back, {{ $user->name }}!</h2>
            <p class="text-sm/4">{{ $user->email }}</p>
        </div>
    </div>
</x-contents>
