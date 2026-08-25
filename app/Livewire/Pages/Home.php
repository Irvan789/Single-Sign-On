<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'Home'])]
class Home extends Component
{
    public ?User $auth;

    public ?User $user;

    public function mount(UserService $userService): void
    {
        $this->auth = Auth::user();
        $this->user = $userService->profile($this->auth);
    }

    public function render(): View
    {
        return view('livewire.pages.home');
    }
}
