<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Secure Area"
        description="Please confirm your password before continuing."
        wire:submit="$js.confirm($event)"
    >
        @csrf

        <x-input-text
            label="Password"
            type="password"
            name="password"
            wire:model="password"
        >
        </x-input-text>

        <x-button
            type="submit"
            testing="confirm"
        >
            Confirm
        </x-button>
    </x-form-auth>
</div>

@script
    <script lang="js">
        $js.confirm = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                await ofetch('{{ route('password.confirm.store') }}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                await $wire.navigate()
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
