<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Email Verification"
        description="Please verify your email address by clicking on the link we just emailed to you."
        wire:submit="$js.verify($event)"
    >
        @csrf

        <x-button type="submit"> Send Verification Email </x-button>
    </x-form-auth>

    <x-separator />

    <span class="-mt-0.5 text-center text-sm/4 font-medium">
        Not {{ $user->username }}?

        <x-form
            method="POST"
            action="{{ route('logout') }}"
            class="contents"
        >
            @csrf

            <button
                type="submit"
                class="text-sm/4 text-[#8a7f70] hover:underline"
            >
                Logout
            </button>
        </x-form>
    </span>
</div>

@script
    <script lang="js">
        $js.verify = async (event) => {
            const formData = new FormData(event.currentTarget)
            const button = event.submitter

            try {
                await ofetch('{{ route('verification.send') }}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                $wire.dispatch('notify', {
                    type: 'success',
                    message: 'A new verification link has been sent to your email address.'
                })
            } catch (error) {
                if (error instanceof Error) {
                    $wire.dispatch('notify', {
                        type: 'error',
                        message: error.data.message
                    })
                }
            } finally {
                button.removeAttribute('disabled')
            }
        }
    </script>
@endscript
