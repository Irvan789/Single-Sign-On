<div class="grid grid-flow-row gap-4">
  <x-auth-header title="Email Verification">
    Please verify your email address by clicking on the link we just emailed to you.
  </x-auth-header>

  <x-form wire:submit="$js.verify($event)">
    @csrf

    <x-button
      type="submit"
      data-wire="verify"
    >
      Send Verification Email
    </x-button>
  </x-form>

  <x-separator />

  <div class="text-[0.9375rem]/4.5 -mt-1 text-center font-medium text-[#3d3530]">
    Not {{ $user->username }}?

    <x-form
      method="POST"
      action="{{ route('logout') }}"
      class="contents"
    >
      @csrf

      <x-button
        type="submit"
        class="text-[0.9375rem]/4.5 w-fit cursor-pointer bg-transparent p-0 text-[#8b7355] hover:bg-transparent hover:underline"
      >
        Logout
      </x-button>
    </x-form>
  </div>
</div>

@script
  <script lang="js">
    $js.verify = async (event) => {
      const formData = new FormData(event.currentTarget)
      const button = event.submitter

      try {
        await ofetch("{{ route('verification.send') }}", {
          method: "POST",
          headers: {
            accept: "application/json"
          },
          body: formData
        })

        return $wire.dispatch('toastify', {
          type: 'success',
          message: 'A new verification link has been sent to your email address.'
        })
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
      } finally {
        button.removeAttribute("disabled")
      }
    }
  </script>
@endscript
