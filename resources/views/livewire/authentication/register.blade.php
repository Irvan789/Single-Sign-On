@php
    $id = rand(10, 99);
@endphp

<div class="mt-3 flex flex-col gap-4">
    <x-form-auth
        title="Create Account"
        description="Enter your details below to create your account."
        wire:submit="$js.register($event)"
    >
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
            x-on:input="$js.lowercase"
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
            testing="register"
            disabled
        >
            Create Account
        </x-button>
    </x-form-auth>

    <x-separator />

    <span class="-mt-0.5 text-center text-sm/4 font-medium">
        Already have an account?,
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
        $js.register = async (event) => {
            const formData = new FormData(event.currentTarget)

            try {
                await ofetch('{{ route('register.store') }}', {
                    method: 'POST',
                    headers: {
                        accept: 'application/json'
                    },
                    body: window.form2Json(formData)
                })

                Livewire.navigate('{{ route('home') }}')
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

        $js.lowercase = (event) => {
            event.target.value = event.target.value.replaceAll(/[^a-zA-Z0-9_]/g, '_').toLowerCase()
        }
    </script>
@endscript
