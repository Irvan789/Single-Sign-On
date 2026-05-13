@php
  $id = rand(10, 99);
@endphp

<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Welcome Back!">
    @if (Route::has('register'))
      Didn't have an account?,
      <a
        href="{{ route('register') }}"
        class="text-[#8b7355] hover:underline"
        wire:navigate
      >
        Create account.
      </a>
    @else
      Enter your email and password below to login.
    @endif
  </x-auth-header>

  <x-form wire:submit="$js.login($event)">
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
          class="text-[0.9375rem]/4 text-[#8b7355] hover:underline"
          wire:navigate
        >
          Forgot Password?
        </a>
      @endif
    </div>

    <x-turnstile
      id="turnstile{{ $id }}"
      wire:model="captcha"
    />

    <x-button
      type="submit"
      data-action="login"
      disabled
    >
      Login
    </x-button>
  </x-form>

  <x-separator />

  <div class="mb-0.5 flex flex-col gap-2">
    <x-social-link :href="route('socials.google')">
      <img
        src="{{ asset('assets/icon/google.svg') }}"
        class="size-4"
        alt="google"
      />
      Continue with Google
    </x-social-link>

    <x-social-link :href="route('socials.github')">
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
        const res = await ofetch("{{ route('login.store') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        Livewire.navigate(
          (
            res.two_factor ?
            "{{ route('two-factor.challenge') }}" :
            "{{ session()->has('url.intended') ? session()->get('url.intended') : route('home') }}"
          )
          .replaceAll('amp;', '')
        )
      } catch (error) {
        await $wire.resetStatus()

        if (error instanceof Error) {
          return $wire.dispatch('toastify', {
            type: 'error',
            message: Object.values(error.data.errors)[0][0] ?? error.message
          })
        }
      }
    }
  </script>
@endscript
