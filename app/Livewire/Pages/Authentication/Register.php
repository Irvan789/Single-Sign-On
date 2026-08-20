<?php

namespace App\Livewire\Pages\Authentication;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Register'])]
class Register extends Component
{
    public string $name;

    public string $username;

    public string $email;

    public string $password;

    public string $password_confirmation;

    public string $captcha;

    public function render()
    {
        return view('livewire.pages.authentication.register');
    }

    public function resetForm(): void
    {
        $this->reset(['password_confirmation']);
    }
}
