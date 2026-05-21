<x-contents>
  @if ($canManageTwoFactor)
    @if ($twoFactorEnabled)
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="-mt-px block space-y-0.5">
          <div class="text-lg/5.5 font-bold">
            Two-Factor Recovery Codes
          </div>

          <div class="text-smd">
            Keep your recovery codes safely for unauthorization access.
          </div>
        </div>

        <div class="flex flex-col gap-4 sm:col-span-2">
          <div class="text-smd grid gap-2 font-mono font-normal sm:grid-cols-2">
            @if (filled($recoveryCodes))
              @foreach ($recoveryCodes as $code)
                <span>
                  {{ $code }}
                </span>
              @endforeach
            @endif
          </div>

          <x-button
            class="xs:max-w-30 ml-auto w-full text-xs/3"
            wire:click="regenerateRecoveryCodes"
            wire:loading.attr="disabled"
          >
            Regenerate Code
          </x-button>
        </div>
      </div>

      <x-separator />

      <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div class="-mt-px block space-y-0.5">
          <div class="text-lg/5.5 font-bold">
            Disable Two-Factor Authentication
          </div>

          <div class="text-smd text-[#b85450]">
            When you disable two-factor authentication, you will be never prompted again for entering authentication.
            code.
          </div>
        </div>

        <x-button
          class="xs:max-w-30 ml-auto w-full text-xs/3"
          wire:click="disableTwoFactor"
          wire:confirm="Are you sure to disable 2FA in your account? This action can't be undone!"
          wire:loading.attr="disabled"
        >
          Disable 2FA
        </x-button>
      </div>
    @else
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="-mt-px block space-y-0.5">
          <div class="text-lg/5.5 font-bold">
            Enable Two-Factor Authentication
          </div>

          <div class="text-smd">
            Follow the step to enable Two-Factor Authentication.
          </div>
        </div>

        <div class="flex flex-col gap-4 sm:col-span-2">
          <div class="block space-y-0.5">
            <div class="text-base/5 font-bold">
              Setup authenticator app
            </div>

            <div class="text-smd">
              Use a phone app like Authy, Google Authenticator, or etc. to get 2FA codes when prompted.
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <div class="text-base/5 font-medium">
              Scan QR Code
            </div>

            @if (empty($qrCodeSvg))
              Loading QR Code
            @else
              {!! $qrCodeSvg !!}

              <div class="text-smd">
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
            class="sm:col-span-2"
            wire:submit="enableTwoFactor"
          >
            <x-input-text
              label="Verify the code from phone app"
              type="number"
              wire:model="code"
            />

            <x-button
              type="submit"
              class="xs:max-w-30 ml-auto w-full text-xs/3"
              x-bind:disabled="$wire.code.length < 6"
            >
              Verify
            </x-button>
          </x-form>
        </div>
      </div>
    @endif
  @endif
</x-contents>
