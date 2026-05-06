<x-contents>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        {{ $user->passwordless ? 'Create' : 'Change' }} Password
      </div>

      <div class="text-[0.9375rem]/4.5 mt-1">
        {{ $user->passwordless
            ? 'Before you can login using email and password, You must create a password first.'
            : 'Update your password associated with your account.' }}
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="updatePassword"
    >
      @if (!$user->passwordless)
        <x-input-text
          label="Current Password"
          type="password"
          wire:model="current_password"
        />
      @endif

      <x-input-text
        label="New Password"
        type="password"
        wire:model="password"
      />

      <x-input-text
        label="Confirm New Password"
        type="password"
        wire:model="password_confirmation"
      />

      <x-button
        type="submit"
        class="xs:max-w-34 ml-auto w-full"
      >
        {{ $user->passwordless ? 'Create' : 'Change' }} Password
      </x-button>
    </x-form>
  </div>

  @if ($canManageTwoFactor)
    <x-separator />

    <div class="xs:flex-row xs:items-center flex flex-col justify-between gap-4">
      <div class="block">
        <div class="text-lg/4.5 font-bold">
          Two-Factor Authentication
        </div>

        <div class="text-[0.9375rem]/4.5 mt-1">
          Enable or Disable Two-Factor Authentication
        </div>
      </div>

      <x-anchor-button
        href="{{ route('security.2fa') }}"
        class="xs:max-w-34 ml-auto w-full"
        wire:navigate
      >
        Manage 2FA
      </x-anchor-button>
    </div>
  @endif
</x-contents>
