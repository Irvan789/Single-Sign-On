<?php

namespace App\Livewire\Pages\Authentication;

use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Email Verification'])]
class VerifyEmail extends Component
{
    public ?User $user;

    public function boot(UserService $user)
    {
        $this->user = $user->profile();

        if ($this->user->email_verified_at) {
            $this->redirectRoute('home', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.pages.authentication.verify-email', [
            'user' => $this->user,
        ]);
    }
}
