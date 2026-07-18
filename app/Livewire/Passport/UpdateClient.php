<?php

namespace App\Livewire\Passport;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\Passport;
use Livewire\Component;

class UpdateClient extends Component
{
    public mixed $user;

    public mixed $client;

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

    public function mount(Request $request)
    {
        try {
            $id = $request->query('id', 0);

            $this->user = Auth::user();

            $this->client = $this->user->oauthApps()->findOrFail($id);

            $this->name = $this->client->name;

            $this->callbacks = $this->client->redirect_uris;
        } catch (Exception $error) {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.passport.update-client', [
            'client' => $this->client
        ])
            ->layout('layouts::app', [
                'title' => 'Update Passport Client',
                'user' => $this->user
            ]);
    }

    public function updatePassportClient()
    {
        $this->validate([
            'name' => ['required', 'string'],
            'callbacks' => ['required', 'array'],
            'callbacks.*' => ['required', 'string', 'url']
        ]);

        Passport::client()->findOrFail($this->client->id)
            ->update([
                'name' => $this->name,
                'redirect_uris' => $this->callbacks
            ]);

        Session::flash('status', 'Client updated successfully!');

        $this->redirectRoute('passport.home', navigate: true);
    }
}
