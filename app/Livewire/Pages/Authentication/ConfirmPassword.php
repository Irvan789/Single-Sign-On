<?php

namespace App\Livewire\Pages\Authentication;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Secure Area'])]
class ConfirmPassword extends Component
{
    public string $password;

    public function render()
    {
        return view('livewire.pages.authentication.confirm-password');
    }

    public function navigate(): void
    {
        $this->redirectIntended(route('home'), navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset('password');
    }
}
