<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Reset Password'])]
class ResetPassword extends Component
{
    public string $password = '';

    public string $password_confirmation = '';

    public function render()
    {
        return view('livewire.auth.reset-password');
    }

    public function navigate(string $message): void
    {
        Session::flash('status', $message);

        $this->redirectRoute('login', navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset(['password_confirmation']);
    }
}
