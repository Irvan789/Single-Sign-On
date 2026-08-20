<div
    class="mt-3 flex flex-col gap-4"
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
    <x-form-auth
        title="Two-Factor Authentication"
        x-text="
            showRecoveryInput
                ? 'Enter one of your recovery code to continue.'
                : 'Enter the authentication code from your authenticator application.'
        "
        wire:submit="$js.twoFactor($event)"
    >
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
            x-bind:disabled="
                showRecoveryInput ? $wire.recovery_code.length < 21 : $wire.code.length < 6
            "
            data-wire="twoFactor"
        >
            Continue
        </x-button>
    </x-form-auth>

    <button
        type="button"
        class="text-sm/4 font-medium text-[#4a3f35] hover:underline"
        x-on:click="switchInputCode()"
        x-text="
            showRecoveryInput ? 'Using your authentication code' : 'Try using your recovery code'
        "
    ></button>

    <x-separator />

    <span class="-mt-0.5 text-center text-sm/4 font-medium">
        Return to
        <a
            href="{{ route('login') }}"
            class="text-[#8a7f70] hover:underline"
            wire:navigate
        >
            Login
        </a>
    </span>
</div>

@script
    <script lang="js">
        $js.twoFactor = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                const res = await ofetch('{{ route('two-factor.login.store') }}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                Livewire.navigate('{{ session()->has('url.intended') ? session()->get('url.intended') : route('home') }}'.replaceAll('amp;', ''))
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
