<?php

namespace App\Livewire;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use ProfileValidationRules, PasswordValidationRules;

    public mixed $user;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public mixed $socials_google;

    public mixed $socials_github;

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

        if ($this->user) {
            $this->name = $this->user->name;

            $this->username = $this->user->username;

            $this->email = $this->user->email;

            $this->socials_google = $this->user->socialAccounts()->where('provider', 'google')->first();

            $this->socials_github = $this->user->socialAccounts()->where('provider', 'github')->first();
        }
    }

    public function render()
    {
        if (Session::has('status') || Session::has('error')) {
            $this->dispatch('toastify', [
                'type' => Session::has('error') ? 'error' : 'success',
                'message' => Session::get('error') ?? Session::get('status')
            ]);
        }

        return view('livewire.profile')->layout('layouts::app', [
            'title' => 'My Profile',
            'user' => $this->user
        ]);
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail && !$this->user->hasVerifiedEmail();
    }

    public function resendVerificationNotification(): void
    {
        if ($this->user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('profile', absolute: false));

            return;
        }

        $this->user->sendEmailVerificationNotification();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'A new verification link has been sent to your email address.'
        ]);
    }

    public function updateProfileInformation(): void
    {
        $validated = $this->validate($this->profileRules($this->user->id));

        $validated['username'] = preg_replace('/[^a-zA-Z0-9]/', '_', $validated['username']);

        $this->user->fill($validated);

        if ($this->user->isDirty('email')) {
            $this->user->email_verified_at = null;
        }

        $this->user->save();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'Profile updated successfully!'
        ]);
    }

    public function deleteAccount(Logout $logout): void
    {
        try {
            $this->validate([
                'password' => $this->currentPasswordRules()
            ]);

            $this->reset('password');

            $this->user->oauthApps()->delete();

            $this->user->socialAccounts()->delete();

            tap(Auth::user(), $logout(...))->delete();

            $this->redirect(route('login'), navigate: true);
        } catch (ValidationException $error) {
            $this->reset('password');

            throw $error;
        }
    }
}
