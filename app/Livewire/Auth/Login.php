<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth', ['title' => 'Login'])]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $captcha = '';

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

    public function render()
    {
        if (Session::has('status') || Session::has('error')) {
            $this->dispatch('toastify', [
                'type' => Session::has('error') ? 'error' : 'success',
                'message' => Session::get('status') ?? Session::get('error')
            ]);
        }

        return view('livewire.auth.login');
    }

    public function resetStatus(): void
    {
        $this->reset('password');
    }
}
