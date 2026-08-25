<?php

namespace App\Livewire\Pages\Authentication;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Email Verification'])]
class VerifyEmail extends Component
{
    public ?User $auth;

    public ?User $user;

    public function boot(UserService $userService)
    {
        $this->auth = Auth::user();
        $this->user = $userService->profile($this->auth);

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
