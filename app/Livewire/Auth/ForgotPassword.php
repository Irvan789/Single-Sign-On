<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Forgot Password'])]
class ForgotPassword extends Component
{
    public string $email = '';

    public string $captcha = '';

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }

    public function navigate(string $message): void
    {
        Session::flash('status', $message);

        $this->redirectRoute('login', navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset();
    }
}
