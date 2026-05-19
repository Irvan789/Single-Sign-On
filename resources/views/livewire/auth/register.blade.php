@php
  $id = rand(10, 99);
@endphp

<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Create Account">
    Enter your details below to create your account
  </x-auth-header>

  <x-form wire:submit="$js.register($event)">
    @csrf

    <x-input-text
      label="Name"
      type="text"
      name="name"
      wire:model="name"
    />

    <x-input-text
      label="Username"
      type="text"
      name="username"
      wire:model="username"
      x-on:input="$js.usernameInput"
    />

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

    <x-input-text
      label="Confirm Password"
      type="password"
      name="password_confirmation"
      wire:model="password_confirmation"
    />

    <x-turnstile
      id="turnstile{{ $id }}"
      wire:model="captcha"
    />

    <x-button
      type="submit"
      data-action="register"
      disabled
    >
      Create Account
    </x-button>
  </x-form>

  <x-separator />

  <div class="text-smd -mt-1 text-center font-medium text-[#3d3530]">
    Already have an account?,
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
    $js.register = async (event) => {
      const formData = new FormData(event.currentTarget)

      try {
        await ofetch("{{ route('register.store') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        Livewire.navigate("{{ route('home') }}")
      } catch (error) {
        await $wire.resetForm()

        if (error instanceof Error) {
          return $wire.dispatch('toastify', {
            type: 'error',
            message: Object.values(error.data.errors)[0][0] ?? error.message
          })
        }
      }
    }

    $js.usernameInput = (event) => {
      event.target.value = event.target.value.replaceAll(/[^a-zA-Z0-9_]/g, "_").toLowerCase()
    }
  </script>
@endscript
