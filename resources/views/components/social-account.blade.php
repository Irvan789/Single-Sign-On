<div class="flex flex-row items-center justify-between gap-4">
  <div class="flex flex-col">
    <div class="text-smd font-semibold">
      {{ $name }}
    </div>

    <div class="break-all text-sm/4">
      {{ $email }}
    </div>
  </div>

  @if (isset($url))
    <a
      href="{{ $url }}"
      class="rounded-xs min-w-16 bg-[#514a43] px-3 py-2 text-center text-xs/3 capitalize text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
      {{ $attributes }}
    >
      {{ $slot }}
    </a>
  @else
    <button
      type="button"
      class="rounded-xs min-w-16 bg-[#514a43] px-3 py-2 text-center text-xs/3 capitalize text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530] disabled:bg-[#3d3530cc] disabled:hover:bg-[#3d3530cc]"
      @if ($email == '-') disabled @endif
      {{ $attributes }}
    >
      {{ $slot }}
    </button>
  @endif
</div>
