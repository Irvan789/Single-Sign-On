<?php

namespace App\Livewire\Components\Users;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Social;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UpdateAccounts extends Component
{
    use PasswordValidationRules, ProfileValidationRules;

    public ?User $user;

    public string $name;

    public string $username;

    public string $email;

    public string $password;

    public string $password_confirmation;

    #[Locked]
    public bool $canManageTwoFactor;

    #[Locked]
    public bool $twoFactorEnabled;

    public function mount(string $id)
    {
        $this->user = User::where('id', $id)->firstOrFail();

        if ($this->user->id == Auth::user()->id) {
            return $this->redirectRoute('profile', navigate: true);
        }

        $this->name = $this->user->name;

        $this->username = $this->user->username;

        $this->email = $this->user->email;

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
        }
    }

    public function render()
    {
        return view('livewire.users.update-accounts', [
            'social_google' => $this->user->social('google'),
            'social_github' => $this->user->social('github'),
        ])->layout('layouts::app', [
            'title' => $this->name.' Profile',
        ]);
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail && ! $this->user->hasVerifiedEmail();
    }

    public function updateProfileInformation(): void
    {
        $this->name = Str::trim($this->name);

        $this->username = preg_replace('/[\s+]/', '_', strtolower($this->username));

        $validated = $this->validate($this->profileRules($this->user->id), $this->profileRulesErrorMessages());

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            if (is_null($this->user->email_verified_at)) {
                $this->notify('error', 'Can\'t change email address for unverified user!');

                return;
            }

            $this->user->email_verified_at = null;
        }

        $this->user->save();

        $this->notify('success', 'User profile updated successfully!');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'password' => $this->passwordRules(),
        ]);

        $this->user->update([
            'password' => $validated['password'],
            'passwordless' => false,
        ]);

        $this->reset('password', 'password_confirmation');

        $this->notify('success', 'User password updated successfully!');
    }

    public function unlinkSocialAccount(string $provider): void
    {
        $socialAccontByEmail = Social::getUserBySocialAccountEmail($provider, $this->user->email);

        if ($socialAccontByEmail->first()) {
            $socialAccontByEmail->delete();

            if ($provider == 'google') {
                $this->user->social('google')->delete();
            }

            if ($provider == 'github') {
                $this->user->social('github')->delete();
            }

            $this->notify('success', 'Social Account unlinked successfully!');
        }
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disableTwoFactorAuthentication)
    {
        $disableTwoFactorAuthentication($this->user);

        $this->twoFactorEnabled = false;

        $this->notify('success', 'Two-Factor disable successfully!');
    }

    public function deleteAccount(): void
    {
        $this->user->oauthApps()->delete();

        $this->user->socials()->delete();

        $this->user->delete();

        session()->flash('notify', [
            'type' => 'success',
            'message' => 'User deleted successfully!',
        ]);

        $this->redirectRoute('users.manage.accounts', navigate: true);
    }
}
