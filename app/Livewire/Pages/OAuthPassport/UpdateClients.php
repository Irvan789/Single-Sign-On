<?php

namespace App\Livewire\Pages\OAuthPassport;

use App\Livewire\Forms\PassportClientForm;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'Update OAuth Client'])]
class UpdateClients extends Component
{
    public ?User $user;

    public ?Client $client;

    public PassportClientForm $passportClientForm;

    public function mount(string $id, UserService $userService): void
    {
        $this->user = $userService->profile();

        $this->client = $this->user->oauthApps()->findOrFail($id);

        $this->passportClientForm->set($this->client);
    }

    public function render(): View
    {
        return view('livewire.pages.oauth-passports.update-clients', [
            'client' => $this->client,
        ]);
    }

    public function updatePassportClient(): void
    {
        $data = $this->passportClientForm->data();

        Passport::client()->findOrFail($this->client->id)
            ->update([
                'name' => $data['name'],
                'redirect_uris' => $data['callbacks'],
            ]);

        session()->flash('notify-session', [
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

        session()->flash('notify-session', [
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
