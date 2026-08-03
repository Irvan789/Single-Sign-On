<x-layouts::auth title="Authorize Error">
    <div class="flex flex-col gap-3.5">
        <div class="mt-3 space-y-0.5">
            <h1 class="h-5 font-serif text-2xl/5 font-medium text-[#4a3f35]">Invalid Request</h1>

            <p class="text-[0.938rem]/4.75 font-medium text-[#8a7f70]">{{ $message }}</p>
        </div>

        <a
            href="{{ $redirect_uri }}"
            class="w-fit rounded bg-[#6b5a46] px-3 py-2 text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#544636] disabled:bg-[#897967] disabled:hover:bg-[#897967]"
        >
            Return Back
        </a>
    </div>
</x-layouts::auth>
