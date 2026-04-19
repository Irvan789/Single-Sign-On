<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Secure Area'])]
class ConfirmPassword extends Component
{
    public function render()
    {
        return view('livewire.auth.confirm-password');
    }

    public function navigate(): void
    {
        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}
