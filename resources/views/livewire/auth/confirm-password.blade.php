<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Secure Area">
    Please confirm your password before continuing.
  </x-auth-header>

  <x-form x-on:submit.prevent="$js.confirm($event)">
    @csrf

    <x-input-text
      label="Password"
      type="password"
      name="password"
      wire:model="password"
    >
    </x-input-text>

    <x-button type="submit">
      Confirm
    </x-button>
  </x-form>
</div>

@script
  <script lang="js">
    $js.confirm = async (event) => {
      const formData = new FormData(event.currentTarget)
      const button = event.submitter

      try {
        button.setAttribute("disabled", "disabled")

        await ofetch("{{ route('password.confirm.store') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        await $wire.navigate()
      } catch (error) {
        button.removeAttribute("disabled")

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
