<x-contents>
    <x-card
        title="Profile Information"
        description="Update user personal information like name, username, and email."
    >
        <div class="flex flex-col gap-4 lg:flex-row">
            <img
                src="{{ $user->avatar }}?size=128&r=g&d=mp"
                class="size-28 rounded-full border-4 border-[#c9b896]/25"
            />

            <x-form wire:submit="updateProfileInformation">
                <x-input-text
                    label="Name"
                    type="text"
                    wire:model="profileForm.name"
                />

                <x-input-text
                    label="Username"
                    type="text"
                    wire:model="profileForm.username"
                    x-on:input="$js.lowercase"
                />

                <x-input-text
                    label="Email"
                    type="email"
                    wire:model="profileForm.email"
                />

                <hr class="border-t border-[#c9b896]/30" />

                <x-button
                    type="submit"
                    class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
                >
                    Save Change
                </x-button>
            </x-form>
        </div>
    </x-card>

    <x-card
        title="Change Password"
        description="Choose a strong password for this user."
    >
        <x-form wire:submit="updateAccountPassword">
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

    @if (isset($user->socials['google']) || isset($user->socials['github']))
        <x-card
            title="Connected Accounts"
            description="Manage user connected social accounts."
        >
            <div class="flex flex-col gap-4">
                @if (isset($user->socials['google']))
                    <x-social-account
                        name="Google"
                        email="{{ $user->socials['google']->email }}"
                        wire:click="unlinkSocialAccount('google')"
                        wire:confirm="Are you sure to unlink user social account? This action can't be undone!"
                        wire:loading.attr="disabled"
                    >
                        Unlink
                    </x-social-account>
                @endif

                @if (isset($user->socials['github']))
                    <x-social-account
                        name="Google"
                        email="{{ $user->socials['github']->email }}"
                        wire:click="unlinkSocialAccount('github')"
                        wire:confirm="Are you sure to unlink user social account? This action can't be undone!"
                        wire:loading.attr="disabled"
                    >
                        Unlink
                    </x-social-account>
                @endif
            </div>
        </x-card>
    @endif

    @if ($canManageTwoFactor && $twoFactorEnabled)
        <x-card
            variant="danger"
            title="Disable Two-Factor Authentication"
            description="When you disable two-factor authentication, this user will be never prompted again for entering authentication code."
            class="flex flex-col justify-between sm:flex-row sm:items-center"
        >
            <x-button
                variant="danger"
                class="h-fit w-full max-w-28 text-xs/3"
                wire:click="disableTwoFactor"
                wire:confirm="Are you sure to disable 2FA for this account? This action can't be undone!"
                wire:loading.attr="disabled"
            >
                Disable 2FA
            </x-button>
        </x-card>
    @endif

    <x-card
        variant="danger"
        title="Delete Account"
        description="Warning: All user data will be deleted immediately."
        class="flex flex-col justify-between sm:flex-row sm:items-center"
    >
        <x-button
            variant="danger"
            class="h-fit w-full max-w-28 text-xs/3"
            wire:click="deleteAccount"
            wire:confirm="Are you sure to delete this account? This action can't be undone!"
            wire:loading.attr="disabled"
        >
            Delete Account
        </x-button>
    </x-card>

    @script
        <script lang="js">
            $js.lowercase = (event) => {
                event.target.value = event.target.value
                    .replaceAll(/[^a-zA-Z0-9_]/g, '_')
                    .toLowerCase()
            }
        </script>
    @endscript
</x-contents>
