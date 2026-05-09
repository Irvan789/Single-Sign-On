<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination, WithoutUrlPagination;

    public mixed $user;

    public string $search = '';

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        if (Session::has('status') || Session::has('error')) {
            $this->dispatch('toastify', [
                'type' => Session::has('error') ? 'error' : 'success',
                'message' => Session::get('error') ?? Session::get('status')
            ]);
        }

        return view('livewire.users.home', [
            'users' => User::getUsersWithSocialAccounts($this->user)
                ->when($this->search, function ($query, $search) {
                    $query->where('name', 'ilike', "{$search}%");
                })
                ->paginate(10)
                ->onEachSide(1)
        ])->layout('layouts::app', [
            'title' => 'Manage Users',
            'user' => $this->user
        ]);
    }

    public function searchUser(): void
    {
        $this->resetPage();
    }
}
