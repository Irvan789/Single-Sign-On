<div
  class="grid grid-flow-row gap-4"
  x-cloak
  x-data="{
      showRecoveryInput: false,
      code: '',
      recovery_code: '',
      switchInputCode() {
          this.showRecoveryInput = !this.showRecoveryInput
  
          this.code = ''
          this.recovery_code = ''
      }
  }"
>
  <x-auth-header
    title="Two-Factor Authentication"
    x-show="showRecoveryInput"
  >
    Enter one of your recovery code to continue.
  </x-auth-header>

  <x-auth-header
    title="Two-Factor Authentication"
    x-show="!showRecoveryInput"
  >
    Enter the authentication code from your authenticator application.
  </x-auth-header>

  <x-form wire:submit="$js.twoFactor($event)">
    @csrf
    <x-input-text
      label="Recovery Code"
      type="text"
      name="recovery_code"
      x-show="showRecoveryInput"
      x-model="recovery_code"
      wire:model="recovery_code"
    />

    <x-input-text
      label="Authentication Code"
      type="text"
      name="code"
      x-show="!showRecoveryInput"
      x-model="code"
      wire:model="code"
    />

    <x-button
      type="submit"
      x-bind:disabled="showRecoveryInput ? $wire.recovery_code.length < 21 : $wire.code.length < 6"
    >
      Continue
    </x-button>
  </x-form>

  <button
    type="button"
    class="mx-auto w-fit text-[0.9375rem]/4 font-medium text-[#8b7355] hover:underline"
    x-on:click="switchInputCode()"
    x-text="showRecoveryInput ? 'Have your phone?, Using your authentication code' : 'Can\'t access your phone?, Using your recovery code'"
  >
  </button>

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
    $js.twoFactor = async (event) => {
      const formData = new FormData(event.currentTarget)

      try {
        const res = await ofetch("{{ route('two-factor.login.store') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        Livewire.navigate("{{ route('home') }}")
      } catch (error) {
        await $wire.resetCode()

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

