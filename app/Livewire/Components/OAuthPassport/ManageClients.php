<?php

namespace App\Livewire\Components\OAuthPassport;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ManageClients extends Component
{
    use WithoutUrlPagination, WithPagination;

    public ?User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.oauth-passports.manage-clients', [
            'clients' => $this->user->oauthApps()->orderBy('created_at', 'desc')->paginate(10)->onEachSide(1),
        ])->layout('layouts::app', [
            'title' => 'OAuth Client',
        ]);
    }
}
