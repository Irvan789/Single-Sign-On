<x-contents>
    @if ($canManageTwoFactor)
        @if ($twoFactorEnabled)
            <x-card
                title="Two-Factor Recovery Codes"
                description="Keep your recovery codes safely for unauthorization access."
            >
                <div class="flex flex-col gap-4">
                    <div
                        class="grid grid-cols-1 gap-2 font-mono text-sm/4 font-normal sm:grid-cols-2"
                    >
                        @if (filled($recoveryCodes))
                            @foreach ($recoveryCodes as $code)
                                <span> {{ $code }} </span>
                            @endforeach
                        @endif
                    </div>

                    <hr class="border-t border-[#c9b896]/30" />

                    <x-button
                        class="w-full max-w-30 justify-center text-xs/3 sm:ml-auto"
                        wire:click="regenerateRecoveryCodes"
                        wire:loading.attr="disabled"
                    >
                        Regenerate Code
                    </x-button>
                </div>
            </x-card>

            <x-card
                variant="danger"
                title="Disable Two-Factor Authentication"
                description="When you disable two-factor authentication, you will be never prompted again for entering authentication code."
                class="flex flex-col justify-between sm:flex-row sm:items-center"
            >
                <x-button
                    variant="danger"
                    class="h-fit w-full max-w-30 text-xs/3"
                    wire:click="disableTwoFactor"
                    wire:confirm="Are you sure to disable 2FA in your account? This action can't be undone!"
                    wire:loading.attr="disabled"
                >
                    Disable 2FA
                </x-button>
            </x-card>
        @else
            <x-card
                title="Enable Two-Factor Authentication"
                description="Follow the step to enable Two-Factor Authentication."
            >
                <div class="flex flex-col gap-4">
                    <div class="block space-y-0.5">
                        <div class="text-base/5 font-medium">Setup authenticator app</div>

                        <div class="text-sm/4.5 text-[#544636]/75">
                            Use a phone app like Authy, Google Authenticator, or etc. to get 2FA
                            codes when prompted.
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="text-base/5 font-medium">Scan QR Code</div>

                        @if (empty($qrCodeSvg))
                            Loading QR Code
                        @else
                            <div
                                class="size-fit rounded-xs bg-white p-4 inset-ring inset-ring-[#c9b896]/40"
                            >
                                {!! $qrCodeSvg !!}
                            </div>

                            <div class="text-sm/4">
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

                    <x-form wire:submit="enableTwoFactor">
                        <x-input-text
                            label="Verify the code from phone app"
                            type="number"
                            wire:model="code"
                        />

                        <x-button
                            type="submit"
                            class="flex w-full max-w-28 justify-center text-xs/3 sm:ml-auto"
                            x-bind:disabled="$wire.code.length < 6"
                        >
                            Verify
                        </x-button>
                    </x-form>
                </div>
            </x-card>
        @endif
    @endif
</x-contents>
