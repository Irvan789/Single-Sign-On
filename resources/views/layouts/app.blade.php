<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('partials.head')
</head>

<body
  class="bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-size-[4rem] bg-[#fffaf0] bg-fixed bg-no-repeat antialiased"
  style="background-image: url('{{ asset('assets/images/background/perlica.webp') }}')"
  x-data="{ showSidebar: false }"
>
  <x-toastify />

  <div
    class="mx-auto flex h-full max-h-full min-w-full flex-row overflow-hidden text-[#3d3530] selection:bg-[#3d3530] selection:text-[#f0ede8]"
  >
    <div
      class="z-1 fixed -right-60 top-0 flex h-full w-full max-w-60 flex-col justify-between gap-4 border-l border-[#c8b96e] bg-[#3d3530] px-2.5 py-4 text-[#f0ede8] transition-all duration-300 lg:relative lg:left-0 lg:right-auto lg:max-h-full lg:border-l-0 lg:border-r"
      :class="[showSidebar ? 'right-0!' : '']"
      x-on:click.outside="showSidebar = false"
    >
      <div class="flex h-full max-h-[calc(100%-2rem)] flex-col gap-4">
        <div class="relative flex flex-row items-center justify-between gap-4 px-1.5">
          <a
            href="/"
            class="hidden text-xl/6 font-bold lg:block"
            wire:navigate
          >
            {{ config('app.name') }}
          </a>

          <div class="inline-flex items-center gap-2 lg:hidden">
            <img
              src="{{ $user->avatar }}?size=128&r=g&d=mp"
              class="size-6 rounded-full"
            />

            <span class="text-sm/4">
              {{ $user->username }}
            </span>
          </div>

          <button
            class="inset-e-1.5 absolute inline-flex lg:hidden"
            x-on:click="showSidebar = false"
          >
            <span class="icon-[tabler--x] size-6"></span>
          </button>
        </div>

        <div class="-mt-0.5 flex flex-col gap-1">
          <div class="mb-1 flex items-center gap-2.5">
            <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
            <div class="h-1.25 w-1.25 rotate-45 bg-[#c8b96e]"></div>
          </div>

          <a
            href="{{ route('home') }}"
            class="{{ Route::is('home') ? 'bg-[#c8b96e2e] text-[#f0ede8]' : 'transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]' }} rounded-xs relative py-2.5 pl-8 pr-2.5 text-sm/4"
            wire:navigate
          >
            <span class="inset-y-2.25 inset-s-2 size-4.5 icon-[mingcute--home-6-line] absolute"></span>
            Home
          </a>

          <a
            href="{{ route('profile') }}"
            class="{{ Route::is('profile') ? 'bg-[#c8b96e2e] text-[#f0ede8]' : 'transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]' }} rounded-xs relative py-2.5 pl-8 pr-2.5 text-sm/4"
            wire:navigate
          >
            <span class="inset-y-2.25 inset-s-2 size-4.5 icon-[mingcute--user-1-line] absolute"></span>
            My Profile
          </a>

          <a
            href="{{ route('security') }}"
            class="{{ Route::is('security') || Route::is('security.2fa') ? 'bg-[#c8b96e2e] text-[#f0ede8]' : 'transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]' }} rounded-xs relative py-2.5 pl-8 pr-2.5 text-sm/4"
            wire:navigate
          >
            <span class="inset-y-2.25 inset-s-2 size-4.5 icon-[mingcute--lock-line] absolute"></span>
            Account Security
          </a>

          @if ($user->role == 'admin')
            <div class="my-1 flex items-center gap-2.5">
              <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
              <div class="h-1.25 w-1.25 rotate-45 bg-[#c8b96e]"></div>
            </div>

            <a
              href="{{ route('users.home') }}"
              class="{{ Route::is('users.home') || Route::is('users.profile') ? 'bg-[#c8b96e2e] text-[#f0ede8]' : 'transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]' }} rounded-xs relative py-2.5 pl-8 pr-2.5 text-sm/4"
              wire:navigate
            >
              <span class="inset-y-2.25 inset-s-2 size-4.5 icon-[mingcute--group-line] absolute"></span>
              Manage Users
            </a>

            <a
              href="{{ route('passport.home') }}"
              class="{{ Route::is('passport.home') || Route::is('passport.create.client') ? 'bg-[#c8b96e2e] text-[#f0ede8]' : 'transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]' }} rounded-xs relative py-2.5 pl-8 pr-2.5 text-sm/4"
              wire:navigate
            >
              <span class="inset-y-2.25 inset-s-2 size-4.5 icon-[mingcute--idcard-line] absolute"></span>
              Passport Client
            </a>
          @endif
        </div>
      </div>

      <form
        method="POST"
        action="{{ route('logout') }}"
        class="contents"
      >
        @csrf

        <button
          class="pl-7.5 rounded-xs relative cursor-pointer py-2.5 pr-2.5 text-start text-sm/4 transition-colors duration-300 hover:bg-[#c8b96e2e] hover:text-[#f0ede8]"
        >
          <span class="inset-y-2.25 inset-s-2 icon-[mingcute--exit-door-line] size-4.5 absolute"></span>
          Logout
        </button>
      </form>
    </div>

    <div class="overlay-scrollbars h-full w-full">
      <div
        class="mx-auto grid min-h-full w-full grid-rows-[max-content_auto_max-content] pb-4 lg:max-w-4xl lg:grid-rows-[auto_max-content] lg:pb-6"
      >
        <div
          class="sticky top-0 z-10 inline-flex w-full items-center justify-between bg-[#3d3530] p-4 text-[#f0ede8] lg:hidden"
        >
          <a
            href="/"
            class="text-xl/6 font-bold"
            wire:navigate
          >
            {{ config('app.name') }}
          </a>

          <button
            class="rounded-xs inline-flex transition-colors duration-300 hover:text-[#c8b96e]"
            x-on:click.stop="showSidebar = !showSidebar"
          >
            <span class="icon-[tabler--menu-2] size-6"></span>
          </button>
        </div>

        {{ $slot }}

        <div class="flex flex-col gap-3 px-4 lg:gap-5 lg:px-5">
          <div class="flex items-center gap-2.5">
            <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
            <div class="h-1.25 w-1.25 rotate-45 bg-[#c8b96e]"></div>
            <div class="h-px flex-1 bg-[#c8b96e4d]"></div>
          </div>

          <span class="block w-full px-4 text-center text-xs/tight font-semibold text-[#3d353080]">
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
  </div>
</body>

</html>
