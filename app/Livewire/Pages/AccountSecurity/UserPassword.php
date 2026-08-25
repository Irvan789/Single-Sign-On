<?php

namespace App\Livewire\Pages\AccountSecurity;

use App\Livewire\Forms\PasswordForm;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'Account Security'])]
class UserPassword extends Component
{
    public ?User $auth;

    public ?User $user;

    public PasswordForm $passwordForm;

    #[Locked]
    public bool $canManageTwoFactor;

    public function mount(UserService $userService): void
    {
        $this->auth = Auth::user();
        $this->user = $userService->profile($this->auth);

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();
    }

    public function render(): View
    {
        return view('livewire.pages.account-security.user-password');
    }

    public function updateAccountPassword(UserService $userService): void
    {
        $data = $this->passwordForm->data($this->user);

        $userService->updateById($this->user->id, $data);

        $this->passwordForm->reset();

        $this->notify('success', 'Password updated successfully!');
    }
}
