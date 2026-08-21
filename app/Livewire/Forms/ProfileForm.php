<?php

namespace App\Livewire\Forms;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Form;

class ProfileForm extends Form
{
    use ProfileValidationRules;

    public User $user;

    public string $name;

    public string $username;

    public string $email;

    public function set(User $user)
    {
        $this->user = $user;

        $this->name = $user->name;

        $this->username = $user->username;

        $this->email = $user->email;
    }

    public function data(string $id): array
    {
        $this->name = Str::trim($this->name);

        $this->username = preg_replace('/[\s+]/', '_', strtolower($this->username));

        $this->validate($this->profileRules($id), $this->profileRulesErrorMessages());

        return $this->only('name', 'username', 'email');
    }
}
