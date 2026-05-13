<?php

namespace App\Livewire\Passport;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.passport.home', [
            'clients' => $this->user->oauthApps()->orderBy('created_at', 'desc')->paginate(10)->onEachSide(1)
        ])->layout('layouts::app', [
            'title' => 'Passport Client',
            'user' => $this->user
        ]);
    }

    public function deletePassportClient(string $clientId)
    {
        try {
            if (!$this->user->oauthApps()->where('id', '=', $clientId)->first()) {
                throw new Exception();
            }

            $this->deletePassportAuthCodeAndToken($clientId);

            Passport::client()
                ->newQuery()
                ->where(['id' => $clientId, 'owner_id' => $this->user->id])
                ->delete();

            $this->dispatch('toastify', [
                'type' => 'success',
                'message' => 'Client deleted successfully!'
            ]);
        } catch (Exception $e) {
            $this->dispatch('toastify', [
                'type' => 'error',
                'message' => 'Client data does not match!'
            ]);
        }
    }

    public function deletePassportClientToken(string $clientId)
    {
        try {
            if (!$this->user->oauthApps()->where('id', '=', $clientId)->first()) {
                throw new Exception();
            }

            $this->deletePassportAuthCodeAndToken($clientId);

            $this->dispatch('toastify', [
                'type' => 'success',
                'message' => 'Client token cleared successfully!'
            ]);
        } catch (Exception $error) {
            $this->dispatch('toastify', [
                'type' => 'error',
                'message' => 'Client data does not match!'
            ]);
        }
    }

    private function deletePassportAuthCodeAndToken(string $clientId)
    {
        User::find($this->user->id)
            ->tokens()
            ->each(function (Token $token) {
                $token->revoke();
                $token->refreshToken?->revoke();
            });

        Passport::authCode()->newQuery()->where('client_id', '=', $clientId)->delete();

        Passport::token()->newQuery()->where('revoked', '=', true)->delete();
        Passport::refreshToken()->newQuery()->where('revoked', '=', true)->delete();

        if (Passport::$deviceCodeGrantEnabled) {
            Passport::deviceCode()->newQuery()->where('revoked', '=', true)->delete();
        }
    }
}
