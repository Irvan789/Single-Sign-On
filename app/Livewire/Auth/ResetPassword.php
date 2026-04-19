<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Reset Password'])]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }

    public function navigate(string $message): void
    {
        Session::flash('status', $message);

        $this->redirectRoute('login', navigate: true);
    }
}
