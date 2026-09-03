@php
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body
    class="bg-[#f7f2ea] bg-size-[4rem] bg-fixed bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-no-repeat text-[#544636] antialiased"
    style="background-image: url('{{ asset('assets/images/background/perlica-v2.webp') }}')"
    x-data="{ showSidebar: false }"
>
    <x-toastify />

    <div class="flex h-full max-h-full min-w-full flex-row overflow-hidden">
        <div
            class="fixed top-0 z-10 flex h-full w-full max-w-68 flex-col justify-between gap-4 border-r border-[#c9b896]/50 bg-[#fbf8f1] transition-all duration-300 lg:relative lg:left-0 lg:z-0 lg:max-h-full lg:transition-none"
            :class="[showSidebar ? 'left-0' : '-left-68']"
            x-on:click.outside="showSidebar = false"
        >
            <div class="flex h-full flex-col">
                <div class="relative flex flex-row items-center justify-between gap-4 p-4">
                    <a
                        href="/"
                        class="font-serif text-[1.75rem]/6 tracking-tight"
                        wire:navigate
                    >
                        {{ config('app.name') }}
                    </a>

                    <button
                        class="absolute inset-e-3 top-4.25 inline-flex lg:hidden"
                        x-on:click="showSidebar = false"
                    >
                        <span class="icon-[tabler--x] size-5"></span>
                    </button>
                </div>

                <div class="overlay-scrollbars h-full max-h-[calc(100%-3.5rem)]">
                    <div class="flex h-full flex-col justify-between">
                        <div class="flex flex-col gap-0.5 px-2">
                            <span class="mb-1 px-2 text-xs/3">Home</span>

                            <a
                                href="{{ route('profile') }}"
                                class="{{ Route::is('profile') ? 'bg-[#c8b96e]/20 text-[#6b5a46]' : 'text-[#8a7f70] transition-colors duration-300 hover:bg-[#c8b96e]/10 hover:text-[#6b5a46]' }} relative rounded py-2.5 pr-2 pl-7.5 text-sm/4 font-medium"
                                wire:navigate
                            >
                                <span
                                    class="icon-[uil--user] absolute inset-y-2.25 inset-s-1.75 size-4.5"
                                ></span>

                                My profile
                            </a>

                            <a
                                href="{{ route('security.password') }}"
                                class="{{ Route::is('security.*') ? 'bg-[#c8b96e]/20 text-[#6b5a46]' : 'text-[#8a7f70] transition-colors duration-300 hover:bg-[#c8b96e]/10 hover:text-[#6b5a46]' }} relative rounded py-2.5 pr-2 pl-7.5 text-sm/4 font-medium"
                                wire:navigate
                            >
                                <span
                                    class="icon-[uil--shield] absolute inset-y-2.25 inset-s-1.75 size-4.5"
                                ></span>

                                Account security
                            </a>

                            @if ($user->isAdmin())
                                <span class="mt-2 mb-1 px-2 text-xs/3">Admin</span>

                                <a
                                    href="{{ route('users.manage.accounts') }}"
                                    class="{{ Route::is('users.*') ? 'bg-[#c8b96e]/20 text-[#6b5a46]' : 'text-[#8a7f70] transition-colors duration-300 hover:bg-[#c8b96e]/10 hover:text-[#6b5a46]' }} relative rounded py-2.5 pr-2 pl-7.5 text-sm/4 font-medium"
                                    wire:navigate
                                >
                                    <span
                                        class="icon-[uil--users-alt] absolute inset-y-2.25 inset-s-1.75 size-4.5"
                                    ></span>

                                    Manage users
                                </a>

                                <a
                                    href="{{ route('oauth.manage.clients') }}"
                                    class="{{ Route::is('oauth.*') ? 'bg-[#c8b96e]/20 text-[#6b5a46]' : 'text-[#8a7f70] transition-colors duration-300 hover:bg-[#c8b96e]/10 hover:text-[#6b5a46]' }} relative rounded py-2.5 pr-2 pl-7.5 text-sm/4 font-medium"
                                    wire:navigate
                                >
                                    <span
                                        class="icon-[uil--key-skeleton-alt] absolute inset-y-2.25 inset-s-1.75 size-4.5"
                                    ></span>

                                    OAuth clients
                                </a>
                            @endif
                        </div>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="px-2 py-4"
                        >
                            @csrf

                            <button
                                class="relative w-full rounded py-2.5 pr-2 pl-7.5 text-start text-sm/4 font-medium text-[#8a7f70] transition-colors duration-300 hover:bg-[#f5e9e4] hover:text-[#a8503d]"
                            >
                                <span
                                    class="icon-[mingcute--exit-line] absolute inset-y-2.25 inset-s-1.75 size-4.5"
                                ></span>

                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="overlay-scrollbars size-full">
            <div class="grid min-h-full w-full grid-rows-[max-content_auto_max-content] pb-4">
                <div
                    class="sticky top-0 z-1 inline-flex w-full items-center justify-between border-b border-[#c9b896]/50 bg-[#fbf8f1]/85 p-4 backdrop-blur lg:justify-end"
                >
                    <div class="flex items-center gap-3">
                        <button
                            class="-mt-px inline-flex items-center transition-colors duration-300 lg:hidden"
                            x-on:click.stop="showSidebar = !showSidebar"
                        >
                            <span class="icon-[tabler--menu-2] size-5"></span>
                        </button>

                        <a
                            href="/"
                            class="font-serif text-[1.75rem]/6 tracking-tight lg:hidden"
                            wire:navigate
                        >
                            {{ config('app.name') }}
                        </a>
                    </div>

                    <div class="inline-flex items-center gap-2">
                        <span class="text-sm/4 font-medium text-[#6b5a46] max-sm:hidden">
                            {{ $user->name }}
                        </span>

                        <img
                            src="{{ $user->avatar }}?size=128&r=g&d=mp"
                            class="size-6 rounded-full"
                        />
                    </div>
                </div>

                <div class="size-full p-4">{{ $slot }}</div>

                <div class="mx-auto mb-1 flex w-full max-w-2xl flex-col gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="h-px flex-1 bg-[#c8b96e]/30"></div>
                        <div class="h-1.25 w-1.25 rotate-45 bg-[#c8b96e]"></div>
                        <div class="h-px flex-1 bg-[#c8b96e]/30"></div>
                    </div>

                    <span class="text-center text-xs/3 font-medium opacity-65">
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
