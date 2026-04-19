<?php

namespace App\Livewire\Passport;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\ClientRepository;
use Livewire\Component;

class CreateClient extends Component
{
    public $user;

    public string $name = '';

    public array $callback = [];

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
        Gate::allowIf(fn(User $user) => $user->role != 'user');

        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.passport.create-client')->layout('layouts::app', [
            'title' => 'Create Passport Client',
            'user' => $this->user
        ]);
    }

    public function createPassportClient(): void
    {
        $this->validate([
            'name' => ['required', 'string'],
            'callback' => ['required', 'array'],
            'callback.*' => ['required', 'string', 'url']
        ]);

        $client = app(ClientRepository::class)->createAuthorizationCodeGrantClient(
            user: Auth::user(),
            name: $this->name,
            redirectUris: $this->callback,
            confidential: true,
            enableDeviceFlow: false
        );

        Session::flash('passport-client', [
            'id' => $client->id,
            'secret' => $client->plainSecret
        ]);

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'Client Created Successfully!'
        ]);

        $this->reset();
    }
}
