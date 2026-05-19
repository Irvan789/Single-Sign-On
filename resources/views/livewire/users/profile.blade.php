<x-contents>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="-mt-px block space-y-0.5">
      <div class="text-lg/5.5 font-bold">
        Profile Information
      </div>

      <div class="text-smd">
        Update user personal information name and username.
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="updateProfileInformation"
    >
      <img
        src="{{ $user->avatar }}?size=128&r=g&d=mp"
        class="size-24 rounded-full"
      />

      <x-input-text
        label="Name"
        type="text"
        wire:model="name"
      />

      <x-input-text
        label="Username"
        type="text"
        wire:model="username"
        x-on:input="usernameInput"
      />

      <x-input-text
        label="Email"
        type="email"
        readonly="{{ $this->hasUnverifiedEmail }}"
        wire:model="email"
      />

      <x-button
        type="submit"
        class="xs:max-w-30 ml-auto w-full text-xs/3"
      >
        Update Profile
      </x-button>
    </x-form>
  </div>

  @if ($socials_google || $socials_github)
    <x-separator />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <div class="-mt-px block space-y-0.5">
        <div class="text-lg/5.5 font-bold">
          Social Accounts
        </div>

        <div class="text-smd">
          Manage user linking social accounts
        </div>
      </div>

      <div class="flex flex-col gap-4 sm:col-span-2">
        @if ($socials_google)
          <x-social-account
            name="Google"
            email="{{ $socials_google->email }}"
            wire:click="unlinkSocialAccount('google')"
            wire:confirm="Are you sure to unlink user social account? This action can't be undone!"
            wire:loading.attr="disabled"
          >
            Unlink
          </x-social-account>
        @endif

        @if ($socials_github)
          <x-social-account
            name="GitHub"
            email="{{ $socials_github->email }}"
            wire:click="unlinkSocialAccount('github')"
            wire:confirm="Are you sure to unlink user social account? This action can't be undone!"
            wire:loading.attr="disabled"
          >
            Unlink
          </x-social-account>
        @endif
      </div>
    </div>
  @endif

  @if ($canManageTwoFactor)
    @if ($twoFactorEnabled)
      <x-separator />

      <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div class="-mt-px block space-y-0.5">
          <div class="text-lg/5.5 font-bold">
            Disable Two-Factor Authentication
          </div>

          <div class="text-smd text-[#b85450]">
            When you disable two-factor authentication, this user will be never prompted again for entering
            authentication
            code.
          </div>
        </div>

        <x-button
          class="xs:max-w-30 ml-auto w-full text-xs/3"
          wire:click="disableTwoFactor"
          wire:confirm="Are you sure to disable 2FA for this account? This action can't be undone!"
          wire:loading.attr="disabled"
        >
          Disable 2FA
        </x-button>
      </div>
    @endif
  @endif

  <x-separator />

  <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
    <div class="-mt-px block space-y-0.5">
      <div class="text-lg/5.5 font-bold">
        Delete Account
      </div>

      <div class="text-smd text-[#b85450]">
        Warning: All user data will be deleted immediately.
      </div>
    </div>

    <x-button
      class="xs:max-w-30 ml-auto w-full text-xs/3"
      wire:click="deleteAccount"
      wire:confirm="Are you sure to delete this account? This action can't be undone!"
      wire:loading.attr="disabled"
    >
      Delete Account
    </x-button>
  </div>
</x-contents>
