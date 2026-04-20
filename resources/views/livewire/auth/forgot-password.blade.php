@php
  $id = rand(10, 99);
@endphp

<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Forgot Password">
    Enter your email to receive a password reset link
  </x-auth-header>

  <x-form wire:submit="$js.forgot($event)">
    @csrf

    <x-input-text
      label="Email"
      type="email"
      name="email"
      wire:model="email"
    />

    <x-turnstile
      id="turnstile{{ $id }}"
      wire:model="captcha"
    />

    <x-button
      type="submit"
      disabled
    >
      Send Password Reset Link
    </x-button>
  </x-form>

  <x-separator />

  <div class="text-[0.9375rem]/4.5 -mt-1 text-center font-medium text-[#3d3530]">
    Return to
    <a
      href="{{ route('login') }}"
      class="text-[#8b7355] hover:underline"
      wire:navigate
    >
      Login
    </a>
  </div>
</div>

@script
  <script lang="js">
    $js.forgot = async (event) => {
      const formData = new FormData(event.currentTarget)

      try {
        const res = await ofetch("{{ route('password.email') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        await $wire.navigate(res.message)
      } catch (error) {
        if (error instanceof zod.ZodError) {
          return $wire.dispatch('toastify', {
            type: 'error',
            message: error.issues[0].message
          })
        }

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
