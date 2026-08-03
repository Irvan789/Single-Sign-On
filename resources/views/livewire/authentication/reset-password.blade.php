<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Reset Password"
        description="Please enter your new password below."
        wire:submit="$js.reset($event)"
    >
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
            testing="reset"
        >
            Reset Password
        </x-button>
    </x-form-auth>
</div>

@script
    <script lang="js">
        $js.reset = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                formData.append('token', '{{ request()->route('token') }}')
                formData.append('email', '{{ request('email') }}')

                const res = await ofetch('{{ route('password.update') }}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                await $wire.navigate(res.message)
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
