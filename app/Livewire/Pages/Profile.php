<?php

namespace App\Livewire\Pages;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use PasswordValidationRules, ProfileValidationRules;

    public ?User $user;

    public string $name;

    public string $username;

    public string $email;

    public string $password;

    public function mount()
    {
        $this->user = Auth::user();

        $this->name = $this->user->name;

        $this->username = $this->user->username;

        $this->email = $this->user->email;
    }

    public function render()
    {
        if (session()->has('notify')) {
            $payload = session()->get('notify');

            $this->notify($payload['type'], $payload['message']);
        }

        return view('livewire.pages.profile', [
            'social_google' => $this->user->social('google'),
            'social_github' => $this->user->social('github'),
        ])->layout('layouts::app', [
            'title' => 'My Profile',
            'user' => $this->user,
        ]);
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail && ! $this->user->hasVerifiedEmail();
    }

    public function resendVerificationNotification(): void
    {
        if ($this->user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('profile', absolute: false));
        }

        $this->user->sendEmailVerificationNotification();

        $this->notify('success', 'A new verification link has been sent to your email address.');
    }

    public function updateProfileInformation(): void
    {
        $this->name = Str::trim($this->name);

        $this->username = preg_replace('/[\s+]/', '_', strtolower($this->username));

        $validated = $this->validate($this->profileRules($this->user->id), $this->profileRulesErrorMessages());

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            $this->user->email_verified_at = null;
        }

        $this->user->save();

        $this->notify('success', 'Profile updated successfully!');
    }

    public function deleteAccount(Logout $logout): void
    {
        try {
            $this->validate([
                'password' => $this->currentPasswordRules(),
            ]);

            $this->reset('password');

            $this->user->oauthApps()->delete();

            $this->user->socials()->delete();

            tap(Auth::user(), $logout(...))->delete();

            $this->redirect(route('login'), navigate: true);
        } catch (ValidationException $error) {
            $this->reset('password');

            throw $error;
        }
    }
}
