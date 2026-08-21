<x-contents>
    <x-card
        title="{{ $user->passwordless ? 'Create' : 'Change' }} Password"
        description="Choose a strong password you don't use elsewhere."
    >
        <x-form wire:submit="updateAccountPassword">
            @if (!$user->passwordless)
                <x-input-text
                    label="Current Password"
                    type="password"
                    wire:model="passwordForm.current_password"
                />
            @endif

            <x-input-text
                label="New Password"
                type="password"
                wire:model="passwordForm.password"
            />

            <x-input-text
                label="Confirm New Password"
                type="password"
                wire:model="passwordForm.password_confirmation"
            />

            <hr class="border-t border-[#c9b896]/30" />

            <x-button
                type="submit"
                class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
            >
                Save Change
            </x-button>
        </x-form>
    </x-card>

    <x-card
        title="2-Step Factor Authentication"
        description="Adds a verification code from your phone whenever you sign in."
        class="flex flex-col justify-between sm:flex-row sm:items-center"
    >
        <x-anchor-button
            href="{{ route('security.two-factor') }}"
            class="h-fit w-full max-w-28 text-xs/3"
            wire:navigate
        >
            Manage 2FA
        </x-anchor-button>
    </x-card>
</x-contents>
