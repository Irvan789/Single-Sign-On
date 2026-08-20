@php
    $id = rand(10, 99);
@endphp

<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Forgot Password"
        description="Enter your email to receive a password reset link."
        wire:submit="$js.forgot($event)"
    >
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
            testing="forgot"
            disabled
        >
            Send Password Reset Link
        </x-button>
    </x-form-auth>

    <x-separator />

    <span class="-mt-0.5 text-center text-sm/4 font-medium">
        Return to
        <a
            href="{{ route('login') }}"
            class="text-[#8a7f70] hover:underline"
            wire:navigate
        >
            Login.
        </a>
    </span>
</div>

@script
    <script lang="js">
        $js.forgot = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                const res = await ofetch('{{ route('password.email') }}', {
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
