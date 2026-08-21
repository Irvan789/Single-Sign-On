<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts::app', ['title' => 'Manage Users'])]
class ManageAccounts extends Component
{
    use WithoutUrlPagination, WithPagination;

    public ?User $user;

    public string $search = '';

    public function mount(UserService $userService): void
    {
        $this->user = $userService->profile();
    }

    public function render(UserService $userService)
    {
        $users = $userService->findAll($this->search);

        return view('livewire.pages.users.manage-accounts', [
            'users' => $users,
        ]);
    }
}
