<?php

namespace App\Livewire\Pages\Users;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\ProfileForm;
use App\Models\Social;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UpdateAccounts extends Component
{
    use PasswordValidationRules, ProfileValidationRules;

    public ?User $user;

    public ProfileForm $profileForm;

    public PasswordForm $passwordForm;

    #[Locked]
    public bool $canManageTwoFactor;

    #[Locked]
    public bool $twoFactorEnabled;

    public function mount(string $id, UserService $userService): void
    {
        $this->user = $userService->findById($id);

        if ($this->user->id == $userService->profile()->id) {
            $this->redirectRoute('profile', navigate: true);

            return;
        }

        $this->profileForm->set($this->user);

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            $this->twoFactorEnabled = $this->user->hasEnabledTwoFactorAuthentication();
        }
    }

    public function hydrate(): void
    {
        $this->user['socials'] = $this->user->socials->keyBy('provider');
    }

    public function render(): View
    {
        return view('livewire.pages.users.update-accounts')
            ->layout('layouts::app', [
                'title' => $this->user->name.' Profile',
            ]);
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail && ! $this->user->hasVerifiedEmail();
    }

    public function updateProfileInformation(UserService $userService): void
    {
        $data = $this->profileForm->data($this->user->id);

        $userService->updateById($this->user->id, $data);

        $this->notify('success', 'User profile updated successfully!');
    }

    public function updateAccountPassword(UserService $userService): void
    {
        $data = $this->passwordForm->data($this->user, true);

        $userService->updateById($this->user->id, $data);

        $this->passwordForm->reset();

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

    public function disableTwoFactor(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication($this->user);

        $this->twoFactorEnabled = false;

        $this->notify('success', 'Two-Factor disable successfully!');
    }

    public function deleteAccount(UserService $userService): void
    {
        $userService->deleteByUser($this->user);

        session()->flash('notify-session', [
            'type' => 'success',
            'message' => 'User deleted successfully!',
        ]);

        $this->redirectRoute('users.manage.accounts', navigate: true);
    }
}
