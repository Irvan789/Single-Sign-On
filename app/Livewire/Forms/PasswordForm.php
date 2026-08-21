<?php

namespace App\Livewire\Forms;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Livewire\Form;

class PasswordForm extends Form
{
    use PasswordValidationRules;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    protected bool $passwordless = false;

    public function data(User $user, ?bool $withoutCurrentPassword = false): array
    {
        $this->validate([
            'current_password' => $user->passwordless || $withoutCurrentPassword
                ? ['nullable']
                : $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ]);

        return $this->only('password', 'passwordless');
    }
}
