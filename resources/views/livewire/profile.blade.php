<x-contents>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        Profile Information
      </div>

      <div class="mt-1 text-[0.9375rem]/5">
        Update your personal information like name, username, and email.
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="updateProfileInformation"
    >
      <div class="flex flex-row items-center gap-4">
        <img
          src="{{ $user->avatar }}?size=128&r=g&d=mp"
          class="size-24 rounded-full"
        />

        <div class="flex flex-col gap-1">
          <div class="text-[0.9375rem]/4.5 font-medium">
            Visit Gravatar to change your avatar
          </div>

          <a
            href="https://gravatar.com/profile/avatars"
            class="rounded-xs inline-flex w-fit items-center justify-center gap-1 bg-[#3d3530e6] px-3 py-2 text-center text-xs/3 text-[#f0ede8] transition-colors duration-300 hover:bg-[#3d3530]"
            target="blank"
          >
            Gravatar
          </a>
        </div>
      </div>

      @if ($this->hasUnverifiedEmail)
        <x-alerts type="danger">
          Your email address is unverified.
          <a
            class="cursor-pointer hover:underline"
            wire:click.prevent="resendVerificationNotification"
          >
            Send Verification Email
          </a>
        </x-alerts>
      @endif

      <x-input-text
        label="Name"
        type="text"
        wire:model="name"
      />

      <x-input-text
        label="Username"
        type="text"
        wire:model="username"
      />

      <x-input-text
        label="Email"
        type="email"
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

  <x-separator />

  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        Social Accounts
      </div>

      <div class="mt-1 text-[0.9375rem]/5">
        Manage your linking social accounts
      </div>
    </div>

    <div class="flex flex-col gap-4 sm:col-span-2">
      @if ($user->passwordless)
        @if ($socials_google)
          <x-social-account
            name="Google"
            :url="route('security')"
            email="{{ $socials_google->email }}"
            wire:navigate
          >
            Account Security
          </x-social-account>
        @endif

        @if ($socials_github)
          <x-social-account
            name="GitHub"
            :url="route('security')"
            email="{{ $socials_github->email }}"
            wire:navigate
          >
            Account Security
          </x-social-account>
        @endif
      @else
        <x-social-account
          name="Google"
          :url="route('socials.google')"
          email="{{ $socials_google ? $socials_google->email : 'Not Linked' }}"
        >
          {{ $socials_google ? 'Unlink' : 'Link' }}
        </x-social-account>

        <x-social-account
          name="GitHub"
          :url="route('socials.github')"
          email="{{ $socials_github ? $socials_github->email : 'Not Linked' }}"
        >
          {{ $socials_github ? 'Unlink' : 'Link' }}
        </x-social-account>
      @endif
    </div>
  </div>

  <x-separator />

  <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="block">
      <div class="text-lg/4.5 font-bold">
        Delete Account
      </div>

      <div class="mt-1 text-[0.9375rem]/5 text-[#b85450]">
        Warning: All your data will be deleted immediately.
      </div>
    </div>

    <x-form
      class="sm:col-span-2"
      wire:submit="deleteUser"
      wire:confirm="Are you sure to delete your account? This action can't be undone!"
    >
      <x-input-text
        label="Current Password"
        type="password"
        wire:model="password"
      />

      <x-button
        type="submit"
        class="xs:max-w-30 ml-auto w-full text-xs/3"
      >
        Delete Account
      </x-button>
    </x-form>
  </div>
</x-contents>
