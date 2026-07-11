<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('partials.head')
</head>

<body
  class="bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-size-[4rem] overflow-hidden bg-[#fffaf0] bg-fixed bg-no-repeat antialiased selection:bg-[#3d3530] selection:text-white"
  style="background-image: url('{{ asset('assets/images/background/perlica.webp') }}')"
>
  <div class="overlay-scrollbars h-full w-full">
    <div class="flex min-h-full w-full py-4">
      <x-toastify />

      <div class="xs:max-w-116 m-auto grid w-full max-w-md grid-rows-[max-content_auto_max-content]">
        <div
          class="text-lg/5.5 inline-flex w-full justify-center divide-x divide-[#c8b96e80] p-4 font-bold text-[#3d3530]"
        >
          {{ $slot }}
        </div>

        <a
          href="{{ route('home') }}"
          class="rounded-xs mx-auto bg-[#514a43] px-3 py-2 text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
          wire:navigate
        >
          Return To Home
        </a>
      </div>
    </div>
  </div>
</body>

</html>
