<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('partials.head')
  @turnstile()
</head>

<body
  class="bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-size-[64px] md:bg-size-[72px] overflow-hidden bg-[#fffaf0] bg-fixed bg-no-repeat selection:bg-[#3d3530] selection:text-[#f0ede8]"
  style="background-image: url('{{ asset('assets/images/background/perlica.webp') }}')"
>
  <div class="overlay-scrollbars h-full w-full">
    <div class="flex min-h-full w-full p-2">
      <x-toastify />

      <div class="md:max-w-116 m-auto grid w-full max-w-md grid-rows-[max-content_auto_max-content]">
        <div class="rounded-xs relative border border-[#c8b96e59] bg-[#ffffffca] p-4 md:p-7">
          <div class="absolute left-4 top-4 hidden h-2.5 w-2.5 border-[#c8b96e80] border-[1px_0_0_1px] md:block">
          </div>
          <div class="absolute right-4 top-4 hidden h-2.5 w-2.5 border-[#c8b96e80] border-[1px_1px_0_0] md:block">
          </div>
          <div class="absolute bottom-4 left-4 hidden h-2.5 w-2.5 border-[#c8b96e80] border-[0_0_1px_1px] md:block">
          </div>
          <div class="absolute bottom-4 right-4 hidden h-2.5 w-2.5 border-[#c8b96e80] border-[0_1px_1px_0] md:block">
          </div>

          <a
            @if (!Route::is('login')) href="{{ route('login') }}" @endif
            class="block w-fit cursor-pointer text-xl/5 font-bold text-[#3d3530]"
            wire:navigate
          >
            {{ config('app.name') }}
          </a>

          {{ $slot }}
        </div>

        <span class="mt-3 text-center text-xs/tight font-semibold text-[#3d353080]">
          irvan789.dev ⋅ Artworks By:
          <a
            href="https://x.com/Yatuno_LLC"
            class="hover:underline"
            target="_blank"
          >
            ゆめにし
          </a>
        </span>
      </div>
    </div>
  </div>
</body>

</html>

