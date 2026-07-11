<x-layouts::auth title="Authorize Error">
  <div class="contents">
    <x-auth-header title="Invalid Request">
      {{ $message }}
    </x-auth-header>

    <a
      href="{{ $redirect_uri }}"
      class="rounded-xs mt-4 block w-fit bg-[#514a43] px-4 py-2.5 text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
    >
      Return Back
    </a>
  </div>
</x-layouts::auth>
