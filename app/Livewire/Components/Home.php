<?php

namespace App\Livewire\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public ?User $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render(): View
    {
        return view('livewire.home')->layout('layouts::app', [
            'title' => 'Home',
            'user' => $this->user,
        ]);
    }
}
