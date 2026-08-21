<?php

namespace App\Livewire\Pages\OAuthPassport;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts::app', ['title' => 'OAuth Clients'])]
class ManageClients extends Component
{
    use WithoutUrlPagination, WithPagination;

    public ?User $user;

    public function mount(UserService $userService): void
    {
        $this->user = $userService->profile();
    }

    public function render(): View
    {
        return view('livewire.pages.oauth-passports.manage-clients', [
            'clients' => $this->user->oauthApps()
                ->orderBy('created_at', 'desc')
                ->paginate(10)->onEachSide(1),
        ]);
    }
}
