<?php

namespace App\Livewire\Security;

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Home extends Component
{
    use PasswordValidationRules;

    public $user;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    #[Locked]
    public bool $canManageTwoFactor;

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

    public function mount(): void
    {
        $this->user = Auth::user();

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();
    }

    public function render()
    {
        if (Session::has('status') || Session::has('error')) {
            $this->dispatch('toastify', [
                'type' => Session::has('error') ? 'error' : 'success',
                'message' => Session::get('error') ?? Session::get('status')
            ]);
        }

        return view('livewire.security.home')->layout('layouts::app', [
            'title' => 'Account Security',
            'user' => $this->user
        ]);
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->user->passwordless ? ['optional'] : $this->currentPasswordRules(),
                'password' => $this->passwordRules()
            ]);

            $this->user->update([
                'password' => $validated['password'],
                'passwordless' => false
            ]);

            $this->reset('current_password', 'password', 'password_confirmation');

            $this->dispatch('toastify', [
                'type' => 'success',
                'message' => 'Password Updated Successfully!'
            ]);
        } catch (ValidationException $error) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $error;
        }
    }
}
