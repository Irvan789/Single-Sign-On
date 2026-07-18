<?php

namespace App\Livewire\Passport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\ClientRepository;
use Livewire\Component;

class CreateClient extends Component
{
    public mixed $user;

    public string $name = '';

    public array $callbacks = [];

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if ($validator->errors()->count() > 0) {
                    $this->dispatch('toastify', [
                        'type' => 'error',
                        'message' => $validator->errors()->all()[0]
                    ]);
                }
            });
        });
    }

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.passport.create-client')
            ->layout('layouts::app', [
                'title' => 'Create Passport Client',
                'user' => $this->user
            ]);
    }

    public function createPassportClient()
    {
        $this->validate([
            'name' => ['required', 'string'],
            'callbacks' => ['required', 'array'],
            'callbacks.*' => ['required', 'string', 'url']
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
            'secret' => $client->plainSecret
        ]);

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'Client created successfully!'
        ]);

        $this->reset();
    }
}
