<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body
    class="overflow-hidden bg-[#f4eee2] bg-size-[4rem] bg-fixed bg-position-[calc(100%-0.5rem)_calc(100%-0.5rem)] bg-no-repeat text-[#544636] antialiased"
    style="background-image: url('{{asset('assets/images/background/perlica-v2.webp')}}')"
>
    <x-toastify />

    <div class="overlay-scrollbars size-full">
        <div class="flex min-h-full w-full px-4 py-8">
            <div
                class="m-auto grid w-full max-w-98 grid-rows-[max-content_auto_max-content] gap-4 md:max-w-md"
            >
                <div
                    class="inline-flex w-full justify-center divide-x divide-[#c8b96e]/50 text-lg/5.5 font-bold"
                >
                    {{ $slot }}
                </div>

                <a
                    href="{{ route('home') }}"
                    class="mx-auto w-fit rounded-xs bg-[#6b5a46] px-3 py-2 text-sm/4 text-[#f0ede8] transition-colors duration-300 hover:bg-[#544636]"
                    wire:navigate
                >
                    Return To Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
