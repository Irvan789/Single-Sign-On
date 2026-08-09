<?php

namespace App\Livewire\Components\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ManageAccounts extends Component
{
    use WithoutUrlPagination, WithPagination;

    public ?User $user;

    public string $search = '';

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.users.manage-accounts', [
            'users' => User::where('id', '<>', $this->user->id)
                ->with('socials')
                ->when($this->search, function ($query, $search) {
                    $query->whereFullText('name', $search);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->onEachSide(1),
        ])->layout('layouts::app', [
            'title' => 'Manage Users',
        ]);
    }

    public function searchUser(): void
    {
        $this->resetPage();
    }
}
