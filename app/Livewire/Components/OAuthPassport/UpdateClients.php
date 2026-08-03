<?php

namespace App\Livewire\Components\OAuthPassport;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Livewire\Component;

class UpdateClients extends Component
{
    public ?User $user;

    public mixed $client;

    public string $name = '';

    public array $callbacks = [];

    public function mount(string $id)
    {
        $this->user = Auth::user();

        $this->client = $this->user->oauthApps()->findOrFail($id);

        $this->name = $this->client->name;

        $this->callbacks = $this->client->redirect_uris;
    }

    public function render()
    {
        return view('livewire.oauth-passports.update-clients', [
            'client' => $this->client,
        ])
            ->layout('layouts::app', [
                'title' => 'Update Passport Client',
            ]);
    }

    public function updatePassportClient()
    {
        $this->validate([
            'name' => ['required', 'string'],
            'callbacks' => ['required', 'array'],
            'callbacks.*' => ['required', 'string', 'url'],
        ]);

        Passport::client()->findOrFail($this->client->id)
            ->update([
                'name' => $this->name,
                'redirect_uris' => $this->callbacks,
            ]);

        session()->flash('notify', [
            'type' => 'success',
            'message' => 'Client updated successfully!',
        ]);

        $this->redirectRoute('oauth.manage.clients', navigate: true);
    }

    public function deletePassportClient(string $id): void
    {
        if (! $this->user->oauthApps()->firstWhere('id', $id)) {
            $this->notify('error', 'Client data does not match!');

            return;
        }

        $this->deletePassportAuthCodeAndToken($id);

        Passport::client()
            ->newQuery()
            ->where(['id' => $id, 'owner_id' => $this->user->id])
            ->delete();

        session()->flash('notify', [
            'type' => 'success',
            'message' => 'Client deleted successfully!',
        ]);

        $this->redirectRoute('oauth.manage.clients', navigate: true);
    }

    private function deletePassportAuthCodeAndToken(string $id): void
    {
        User::find($this->user->id)
            ->tokens()
            ->each(function (Token $token) {
                $token->revoke();
                $token->refreshToken?->revoke();
            });

        Passport::authCode()->newQuery()
            ->where('client_id', $id)
            ->delete();

        Passport::token()->newQuery()
            ->where('revoked', true)
            ->delete();

        Passport::refreshToken()->newQuery()
            ->where('revoked', true)
            ->delete();

        if (Passport::$deviceCodeGrantEnabled) {
            Passport::deviceCode()->newQuery()
                ->where('revoked', true)
                ->delete();
        }
    }
}
