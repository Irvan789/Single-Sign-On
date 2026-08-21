<?php

namespace App\Livewire\Pages\Authentication;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Login'])]
class Login extends Component
{
    public string $email;

    public string $password;

    public bool $remember = false;

    public string $captcha;

    public function render()
    {
        return view('livewire.pages.authentication.login');
    }

    public function resetForm(): void
    {
        $this->reset('password');
    }
}
