<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts::app', ['title' => 'Manage Users'])]
class ManageAccounts extends Component
{
    use WithoutUrlPagination, WithPagination;

    public ?User $auth;

    public ?User $user;

    public string $search = '';

    public function mount(UserService $userService): void
    {
        $this->auth = Auth::user();
        $this->user = $userService->profile($this->auth);
    }

    public function render(UserService $userService)
    {
        $users = $userService->findAll($this->auth, $this->search);

        return view('livewire.pages.users.manage-accounts', [
            'users' => $users,
        ]);
    }
}
