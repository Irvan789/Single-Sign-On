<?php

use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Livewire\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['guest'])->group(function () {
    Route::livewire('/login', Login::class)->name('login');

    if (Features::enabled(Features::registration())) {
        Route::livewire('/register', Register::class)->name('register');
    }

    if (Features::enabled(Features::resetPasswords())) {
        Route::livewire('/forgot-password', ForgotPassword::class)->name('password.request');
        Route::livewire('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    }

    if (Features::enabled(Features::twoFactorAuthentication())) {
        Route::livewire('/two-factor-challenge', TwoFactorChallenge::class)->name('two-factor.challenge');
    }
});

Route::middleware(['auth'])->group(function () {
    if (Features::enabled(Features::emailVerification())) {
        Route::livewire('/verify-email', VerifyEmail::class)->name('verification.notice');
    }

    Route::livewire('/confirm-password', ConfirmPassword::class)->name('password.confirm');
});
