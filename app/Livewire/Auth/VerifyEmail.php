<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Email Verification'])]
class VerifyEmail extends Component
{
    public $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'A new verification link has been sent to your email address.'
        ]);
    }
}
