<?php

namespace App\Livewire;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Livewire\Actions\Logout;
use App\Models\Social;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Profile extends Component
{
    use ProfileValidationRules, PasswordValidationRules;

    public $user;

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

            $this->socials_google = Social::where([
                'user_id' => $this->user->id,
                'provider' => 'google'
            ])->first();

            $this->socials_github = Social::where([
                'user_id' => $this->user->id,
                'provider' => 'github'
            ])->first();
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
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('profile', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'A new verification link has been sent to your email address.'
        ]);
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('toastify', [
            'type' => 'success',
            'message' => 'Profile Updated Successfully!'
        ]);
    }

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules()
        ]);

        $this->user->oauthApps()->where('owner_id', $this->user->id)->delete();

        $this->user->socials()->where('user_id', $this->user->id)->delete();

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect(route('login'), navigate: true);
    }
}
