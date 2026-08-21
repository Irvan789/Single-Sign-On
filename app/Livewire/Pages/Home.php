<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'Home'])]
class Home extends Component
{
    public ?User $user;

    public function mount(UserService $userService): void
    {
        $this->user = $userService->profile();
    }

    public function render(): View
    {
        return view('livewire.pages.home');
    }
}
