<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    @turnstile()
</head>

<body
    class="overflow-hidden bg-[#f4eee2] bg-size-[4rem] bg-fixed bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-no-repeat text-[#544636] antialiased"
    style="background-image: url('{{ asset('assets/images/background/perlica-v2.webp') }}')"
>
    <x-toastify />

    <div class="overlay-scrollbars size-full">
        <div class="flex min-h-full w-full py-8 sm:px-4">
            <div
                class="m-auto grid w-full max-w-md grid-rows-[max-content_auto_max-content] sm:max-w-116"
            >
                <div
                    class="relative rounded-xs bg-[#fbf8f1] p-5 inset-ring inset-ring-[#c9b896]/50 sm:p-7"
                >
                    <div
                        class="absolute top-4 left-4 hidden h-2.5 w-2.5 border-[1px_0_0_1px] border-[#c8b96e] sm:block"
                    ></div>
                    <div
                        class="absolute top-4 right-4 hidden h-2.5 w-2.5 border-[1px_1px_0_0] border-[#c8b96e] sm:block"
                    ></div>
                    <div
                        class="absolute bottom-4 left-4 hidden h-2.5 w-2.5 border-[0_0_1px_1px] border-[#c8b96e] sm:block"
                    ></div>
                    <div
                        class="absolute right-4 bottom-4 hidden h-2.5 w-2.5 border-[0_1px_1px_0] border-[#c8b96e] sm:block"
                    ></div>

                    <a
                        @if (!Route::is('login')) href="{{route('login')}}" @endif
                        class="block h-6 w-fit font-serif text-3xl/6 tracking-tight"
                        wire:navigate
                    >
                        {{ config('app.name') }}
                    </a>

                    {{ $slot }}
                </div>

                <span class="mt-4 text-center text-xs/3 font-medium opacity-65">
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
