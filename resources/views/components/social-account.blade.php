@props(['name', 'url', 'email'])

<div class="flex flex-row items-center justify-between gap-4">
  <div class="flex flex-col">
    <div class="text-[0.9375rem]/4.5 font-semibold">
      {{ $name }}
    </div>

    <div class="break-all text-sm/4 text-zinc-700">
      {{ $email }}
    </div>
  </div>

  <a
    href="{{ $url }}"
    class="rounded-xs h-7 min-w-16 bg-[#3d3530e6] px-2.5 py-1.5 text-center text-xs/4 capitalize text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
    {{ $attributes }}
  >
    {{ $slot }}
  </a>
</div>
