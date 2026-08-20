<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Welcome Back!"
        description="Login to pick up right where you left off."
        wire:submit="$js.login($event)"
    >
        @csrf

        <x-input-text
            label="Email"
            type="email"
            name="email"
            wire:model="email"
        />

        <x-input-text
            label="Password"
            type="password"
            name="password"
            wire:model="password"
        />

        <div class="flex items-center justify-between gap-4 font-medium">
            <x-input-check
                name="remember"
                wire:model="remember"
            >
                Remember Me
            </x-input-check>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm/4 font-medium text-[#4a3f35] hover:underline"
                    wire:navigate
                >
                    Forgot Password?
                </a>
            @endif
        </div>

        <x-turnstile wire:model="captcha" />

        <x-button
            type="submit"
            testing="login"
            disabled
        >
            Login
        </x-button>
    </x-form-auth>

    @if (Route::has('register'))
        <span class="mt-px text-center text-sm/4 font-medium">
            Didn't have an account?,
            <a
                href="{{ route('register') }}"
                class="text-[#8a7f70] hover:underline"
                wire:navigate
            >
                Create account.
            </a>
        </span>
    @endif

    <x-separator />

    <div class="mb-0.5 flex flex-col gap-2">
        <x-social-link :href="route('socials.login', ['provider' => 'google'])">
            <img
                src="{{ asset('assets/icon/google.svg') }}"
                class="size-4"
                alt="google"
            />

            Continue with Google
        </x-social-link>

        <x-social-link :href="route('socials.login', ['provider' => 'github'])">
            <img
                src="{{ asset('assets/icon/github.svg') }}"
                class="size-4"
                alt="github"
            />

            Continue with GitHub
        </x-social-link>
    </div>
</div>

@script
    <script lang="js">
        $js.login = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                const res = await ofetch('{{route('login.store')}}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                Livewire.navigate(
                    (res.two_factor
                        ? '{{ route('two-factor.challenge') }}'
                        : '{{ session()->has('url.intended') ? session()->get('url.intended') : route('home') }}'
                    ).replaceAll('amp;', '')
                )
            } catch (error) {
                await $wire.resetForm()

                if (error instanceof Error) {
                    $wire.dispatch('notify', {
                        type: 'error',
                        message: error.data.message
                    })
                }
            }
        }
    </script>
@endscript
