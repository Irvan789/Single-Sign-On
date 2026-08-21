<?php

namespace App\Livewire\Pages;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Livewire\Actions\Logout;
use App\Livewire\Forms\ProfileForm;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app', ['title' => 'My Profile'])]
class Profile extends Component
{
    use PasswordValidationRules, ProfileValidationRules;

    public ?User $user;

    public ProfileForm $profileForm;

    public string $password;

    public function mount(UserService $userService): void
    {
        $this->user = $userService->profile();

        $this->profileForm->set($this->user);
    }

    public function hydrate(): void
    {
        $this->user['socials'] = $this->user->socials->keyBy('provider');
    }

    public function dehydrate(): void
    {
        $this->reset('password');
    }

    public function render(): View
    {
        if (session()->has('notify')) {
            $payload = session()->get('notify');

            $this->notify($payload['type'], $payload['message']);
        }

        return view('livewire.pages.profile');
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

    public function updateProfileInformation(UserService $userService): void
    {
        $data = $this->profileForm->data($this->user->id);

        $userService->updateById($this->user->id, $data);

        $this->notify('success', 'Profile updated successfully!');
    }

    public function deleteAccount(UserService $userService, Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        $this->reset('password');

        $userService->delete($logout);
    }
}
