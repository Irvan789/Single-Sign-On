<x-contents>
  <div class="text-lg/4.5 font-bold">
    Two-Factor Authentication
  </div>

  @if ($canManageTwoFactor)
    @if ($twoFactorEnabled)
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="block">
          <div class="text-base/4 font-bold">
            Recovery Codes
          </div>

          <div class="mt-1 text-[0.9375rem]/5 sm:mt-2">
            Keep your recovery codes safely for unauthorization access
          </div>
        </div>

        <div class="flex flex-col gap-4 sm:col-span-2">
          <div class="grid gap-2 font-['Consolas'] text-base/5 font-medium sm:grid-cols-2">
            @if (filled($recoveryCodes))
              @foreach ($recoveryCodes as $code)
                <span>
                  {{ $code }}
                </span>
              @endforeach
            @endif
          </div>

          <x-button
            class="max-w-30 ml-auto w-full text-xs/3"
            wire:click="regenerateRecoveryCodes"
            wire:loading.attr="disabled"
          >
            Regenerate Code
          </x-button>
        </div>
      </div>

      <x-separator />

      <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div class="block">
          <div class="text-lg/4.5 font-bold">
            Disable Two-Factor Authentication
          </div>

          <div class="mt-1 text-[0.9375rem]/5 text-[#b85450]">
            When you disable two-factor authentication, you will be never prompted again for entering authentication
            code.
          </div>
        </div>

        <x-button
          class="max-w-30 ml-auto w-full text-xs/3"
          wire:click="disableTwoFactor"
          wire:loading.attr="disabled"
        >
          Disable 2FA
        </x-button>
      </div>
    @else
      <div class="flex flex-col gap-1 text-base/5 font-bold">
        Setup authenticator app

        <div class="text-sm/4.5 font-normal">
          Use a phone app like Authy, Google Authenticator, or etc. to get 2FA codes when prompted.
        </div>
      </div>

      <div class="flex flex-col gap-2">
        <div class="text-base/4 font-bold">
          Scan QR Code
        </div>

        @if (empty($qrCodeSvg))
          Loading QR Code
        @else
          {!! $qrCodeSvg !!}

          <div class="text-[0.9375rem]/5">
            Unable to scan?, enter this code:

            <span class="font-bold">
              @if (empty($manualSetupKey))
                Loading 2FA Key
              @else
                {{ $manualSetupKey }}
              @endif
            </span>

          </div>
        @endif
      </div>

      <x-form
        class="xs:max-w-84 xs:flex-row flex w-full flex-col items-end gap-x-2 gap-y-4"
        wire:submit="enableTwoFactor"
      >
        <x-input-text
          label="Verify the code from phone app"
          type="text"
          wire:model="code"
        />

        <x-button
          type="submit"
          class="text-xs/3.5 xs:max-w-20 w-full"
          x-bind:disabled="$wire.code.length < 6"
        >
          Verify
        </x-button>
      </x-form>
    @endif
  @endif
</x-contents>

