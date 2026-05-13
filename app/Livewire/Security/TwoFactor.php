<?php

namespace App\Livewire\Security;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TwoFactor extends Component
{
    public $user;

    #[Locked]
    public bool $canManageTwoFactor;

    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    #[Locked]
    public array $recoveryCodes = [];

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if ($validator->errors()->count() > 0) {
                    $this->dispatch('toastify', [
                        'type' => 'error',
                        'message' => $validator->errors()->all()[0]
                    ]);
                }
            });
        });
    }

    public function mount(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        $this->user = Auth::user();

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        $enableTwoFactorAuthentication($this->user);

        if ($this->canManageTwoFactor) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();

            $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }
    }

    public function render()
    {
        if (!$this->twoFactorEnabled) {
            try {
                if (!$this->requiresConfirmation) {
                    $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
                }

                $this->qrCodeSvg = $this->user?->twoFactorQrCodeSvg();

                $this->manualSetupKey = decrypt($this->user->two_factor_secret);
            } catch (Exception) {
                $this->addError('error', 'Failed to fetch setup data.');

                $this->reset('qrCodeSvg', 'manualSetupKey');
            }
        } else {
            $this->loadRecoveryCodes();
        }

        return view('livewire.security.two-factor')->layout('layouts::app', [
            'title' => 'Two-Factor Authentication',
            'user' => $this->user
        ]);
    }

    public function enableTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        try {
            $confirmTwoFactorAuthentication($this->user, $this->code);

            $this->twoFactorEnabled = true;

            $this->reset('code', 'manualSetupKey', 'qrCodeSvg');

            $this->loadRecoveryCodes();

            $this->dispatch('toastify', [
                'type' => 'success',
                'message' => 'Two-Factor setup successfully'
            ]);
        } catch (Exception $error) {
            $this->dispatch('toastify', [
                'type' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disableTwoFactorAuthentication)
    {
        $disableTwoFactorAuthentication($this->user);

        $this->twoFactorEnabled = false;

        $this->recoveryCodes = [];

        Session::regenerate();

        Session::flash('status', 'Two-Factor disable successfully');

        $this->redirectRoute('security', navigate: true);
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $newRecoveryCodes = RateLimiter::attempt(
            'regenerate-code.' . $this->user->id,
            1,
            function () use ($generateNewRecoveryCodes) {
                $generateNewRecoveryCodes($this->user);

                $this->loadRecoveryCodes();
            },
            3600
        );

        if (!$newRecoveryCodes) {
            abort(429);

            return;
        }
    }

    private function loadRecoveryCodes(): void
    {
        if ($this->user->hasEnabledTwoFactorAuthentication() && $this->user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($this->user->two_factor_recovery_codes), true);
            } catch (Exception) {
                $this->dispatch('toastify', [
                    'type' => 'error',
                    'message' => 'Failed to load recovery codes.'
                ]);

                $this->recoveryCodes = [];
            }
        }
    }
}
