<?php

namespace App\Livewire\Pages\Authentication;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Reset Password'])]
class ResetPassword extends Component
{
    public string $password;

    public string $password_confirmation;

    public function render()
    {
        return view('livewire.pages.authentication.reset-password');
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
        $this->reset('password_confirmation');
    }
}
