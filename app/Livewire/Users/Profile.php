<?php

namespace App\Livewire\Users;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Social;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Profile extends Component
{
    use ProfileValidationRules, PasswordValidationRules;

    public ?User $user;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public mixed $socials_google;

    public mixed $socials_github;

    #[Locked]
    public bool $canManageTwoFactor;

    #[Locked]
    public bool $twoFactorEnabled;

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

    public function mount(string $id)
    {
        $this->user = User::getUserWithSocialAccount($id)->first();

        if (!$this->user) {
            abort(404);
        }

        if ($this->user->id == Auth::user()->id) {
            return $this->redirectRoute('profile', navigate: true);
        }

        $this->name = $this->user->name;

        $this->username = $this->user->username;

        $this->email = $this->user->email;

        $this->socials_google = $this->user->socialAccounts()->where('provider', 'google')->first();

        $this->socials_github = $this->user->socialAccounts()->where('provider', 'github')->first();

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
        }
    }

    public function render()
    {
        return view('livewire.users.profile')->layout('layouts::app', [
            'title' => $this->name . ' Profile',
            'user' => Auth::user()
        ]);
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail && !$this->user->hasVerifiedEmail();
    }

    public function updateProfileInformation(): void
    {
        $this->name = Str::trim($this->name);

        $this->username = preg_replace('/[\s+]/', '_', strtolower($this->username));

        $validated = $this->validate($this->profileRules($this->user->id), $this->profileRulesErrorMessages());

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            if (is_null($this->user->email_verified_at)) {
                $this->dispatch('toastify', [
                    'type' => 'error',
                    'message' => 'Can\'t change email for unverified user!'
                ]);

                return;
            }

            $this->user->email_verified_at = null;
        }

        $this->user->save();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'User profile updated successfully!'
        ]);
    }

    public function unlinkSocialAccount(string $provider): void
    {
        $socialAccontByEmail = Social::getUserBySocialAccountEmail($provider, $this->user->email);

        if ($socialAccontByEmail->first()) {
            $socialAccontByEmail->delete();

            if ($provider == 'google') {
                $this->socials_google = null;
            }

            if ($provider == 'github') {
                $this->socials_github = null;
            }

            $this->dispatch('toastify', [
                'type' => 'success',
                'message' => 'Social Account unlinked successfully!'
            ]);
        }
    }

    public function disableTwoFactor(DisableTwoFactorAuthentication $disableTwoFactorAuthentication)
    {
        $disableTwoFactorAuthentication($this->user);

        $this->twoFactorEnabled = false;

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'Two-Factor disable successfully!'
        ]);
    }

    public function deleteAccount(): void
    {
        $this->user->oauthApps()->delete();

        $this->user->socialAccounts()->delete();

        $this->user->delete();

        Session::flash('status', 'User deleted successfully!');

        $this->redirectRoute('users.home', navigate: true);
    }
}
