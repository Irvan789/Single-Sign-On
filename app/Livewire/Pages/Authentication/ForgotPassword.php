<?php

namespace App\Livewire\Pages\Authentication;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Forgot Password'])]
class ForgotPassword extends Component
{
    public string $email;

    public string $captcha;

    public function render()
    {
        return view('livewire.pages.authentication.forgot-password');
    }

    public function navigate(string $message): void
    {
        session()->flash('notify-session', [
            'type' => 'success',
            'message' => $message,
        ]);

        $this->redirectRoute('login', navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset('email');
    }
}
