<?php

namespace App\Livewire\Pages\Authentication;

use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Two-Factor Authentication'])]
class TwoFactorChallenge extends Component
{
    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    #[Validate('required|string|size:21', onUpdate: false)]
    public string $recovery_code = '';

    public function boot(TwoFactorLoginRequest $request)
    {
        if (! $request->hasChallengedUser()) {
            return $this->redirectRoute('login', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.pages.authentication.two-factor-challenge');
    }

    public function resetForm(): void
    {
        $this->reset(['code', 'recovery_code']);
    }
}
