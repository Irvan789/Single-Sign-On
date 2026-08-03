<?php

namespace App\Livewire\Components\OAuthPassport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\ClientRepository;
use Livewire\Component;

class CreateClients extends Component
{
    public string $name = '';

    public array $callbacks = [];

    public function render()
    {
        return view('livewire.oauth-passports.create-clients')
            ->layout('layouts::app', [
                'title' => 'Create Passport Client',
            ]);
    }

    public function createPassportClient()
    {
        $this->validate([
            'name' => ['required', 'string'],
            'callbacks' => ['required', 'array'],
            'callbacks.*' => ['required', 'string', 'url'],
        ]);

        $client = app(ClientRepository::class)
            ->createAuthorizationCodeGrantClient(
                user: Auth::user(),
                name: $this->name,
                redirectUris: $this->callbacks,
                confidential: true,
                enableDeviceFlow: false
            );

        Session::flash('passport-client', [
            'id' => $client->id,
            'secret' => $client->plainSecret,
        ]);

        $this->notify('success', 'Client created successfully!');

        $this->reset(['name', 'callbacks']);
    }
}
