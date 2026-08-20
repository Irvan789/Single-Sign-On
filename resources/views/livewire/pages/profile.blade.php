<x-contents x-data="{ modalDelete: false }">
    <x-card
        title="Profile Information"
        description="Update your personal information like name, username, and email."
    >
        <div class="flex flex-col gap-4 lg:flex-row">
            <div class="flex flex-col items-center gap-2">
                <img
                    src="{{ $user->avatar }}?size=128&r=g&d=mp"
                    class="size-28 rounded-full border-4 border-[#c9b896]/25"
                />

                <a
                    href="https://gravatar.com/profile/avatars"
                    class="inline-flex w-fit items-center justify-center gap-1 rounded bg-[#6b5a46] px-3 py-2 text-center text-xs/3 text-[#f0ede8] transition-colors duration-300 hover:bg-[#544636]"
                    target="_blank"
                >
                    Gravatar
                </a>
            </div>

            <x-form wire:submit="updateProfileInformation">
                <x-input-text
                    label="Name"
                    type="text"
                    wire:model="name"
                />

                <x-input-text
                    label="Username"
                    type="text"
                    wire:model="username"
                    x-on:input="$js.lowercase"
                />

                <x-input-text
                    label="Email"
                    type="email"
                    wire:model="email"
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
        title="Connected Accounts"
        description="Link a social account to sign in faster without a password."
    >
        <div class="flex flex-col gap-4">
            @if ($user->passwordless)
                @if ($social_google)
                    <x-social-account
                        name="Google"
                        url="{{ route('security.password') }}"
                        email="{{ $social_google->email }}"
                        wire:navigate
                    >
                        Account Security
                    </x-social-account>
                @endif

                @if ($social_github)
                    <x-social-account
                        name="GitHub"
                        url="{{ route('security.password') }}"
                        email="{{ $social_github->email }}"
                        wire:navigate
                    >
                        Account Security
                    </x-social-account>
                @endif
            @else
                <x-social-account
                    name="Google"
                    url="{{ route('socials.login', ['provider' => 'google']) }}"
                    email="{{ $social_google ? $social_google->email : 'Not Linked' }}"
                >
                    {{ $social_google ? 'Unlink' : 'Link' }}
                </x-social-account>

                <x-social-account
                    name="GitHub"
                    url="{{ route('socials.login', ['provider' => 'github']) }}"
                    email="{{ $social_github ? $social_github->email : 'Not Linked' }}"
                >
                    {{ $social_github ? 'Unlink' : 'Link' }}
                </x-social-account>
            @endif
        </div>
    </x-card>

    <x-card
        variant="danger"
        title="Delete Account"
        description="Warning: All your data will be deleted immediately."
        class="flex flex-col justify-between sm:flex-row sm:items-center"
    >
        <x-button
            variant="danger"
            class="h-fit w-full max-w-28 text-xs/3"
            x-on:click="modalDelete = !modalDelete"
        >
            Delete Account
        </x-button>
    </x-card>

    <x-modal-dialog
        title="Delete Account"
        description="Enter your password to continue account deletion."
        x-show="modalDelete"
        x-on:click.outside="modalDelete = false"
    >
        <x-form
            class="mt-4"
            wire:submit="deleteAccount"
            wire:confirm="Are you sure to delete your account? This action can't be undone!"
        >
            <x-input-text
                label="Your Password"
                type="password"
                wire:model="password"
            />

            <x-button
                type="submit"
                variant="danger"
                class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
            >
                Delete Account
            </x-button>
        </x-form>
    </x-modal-dialog>

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
