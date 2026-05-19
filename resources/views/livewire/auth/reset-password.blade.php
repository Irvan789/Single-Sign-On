<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Reset Password">
    Please enter your new password below
  </x-auth-header>

  <x-form wire:submit="$js.reset($event)">
    @csrf

    <x-input-text
      label="Password"
      type="password"
      name="password"
      wire:model="password"
    >
    </x-input-text>

    <x-input-text
      label="Confirm Password"
      type="password"
      name="password_confirmation"
      wire:model="password_confirmation"
    >
    </x-input-text>

    <x-button
      type="submit"
      data-action="reset"
    >
      Reset Password
    </x-button>
  </x-form>
</div>

@script
  <script lang="js">
    $js.reset = async (event) => {
      const formData = new FormData(event.currentTarget)

      try {
        formData.append("token", "{{ request()->route('token') }}")
        formData.append("email", "{{ request('email') }}")

        const res = await ofetch("{{ route('password.update') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        await $wire.navigate(res.message)
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
  </script>
@endscript
