<?php

namespace App\Livewire\Pages\AccountSecurity;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserPassword extends Component
{
    use PasswordValidationRules;

    public ?User $user;

    public string $current_password;

    public string $password;

    public string $password_confirmation;

    #[Locked]
    public bool $canManageTwoFactor;

    public function mount(): void
    {
        $this->user = Auth::user();

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();
    }

    public function render()
    {
        return view('livewire.pages.account-security.user-password')
            ->layout('layouts::app', [
                'title' => 'Account Security',
            ]);
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => $this->user->passwordless ? ['nullable'] : $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ]);

        $this->user->update([
            'password' => $validated['password'],
            'passwordless' => false,
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->notify('success', 'Password updated successfully!');
    }
}
