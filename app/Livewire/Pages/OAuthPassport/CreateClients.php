<?php

namespace App\Livewire\Pages\OAuthPassport;

use App\Livewire\Forms\PassportClientForm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\ClientRepository;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'Create OAuth Client'])]
class CreateClients extends Component
{
    public PassportClientForm $passportClientForm;

    public function render(): View
    {
        return view('livewire.pages.oauth-passports.create-clients');
    }

    public function createPassportClient(): void
    {
        $data = $this->passportClientForm->data();

        $client = app(ClientRepository::class)
            ->createAuthorizationCodeGrantClient(
                user: Auth::user(),
                name: $data['name'],
                redirectUris: $data['callbacks'],
                confidential: true,
                enableDeviceFlow: false
            );

        Session::flash('passport-client', [
            'id' => $client->id,
            'secret' => $client->plainSecret,
        ]);

        $this->notify('success', 'Client created successfully!');

        $this->passportClientForm->reset();
    }
}
