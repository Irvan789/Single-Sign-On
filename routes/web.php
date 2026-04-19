<?php

use App\Http\Controllers\SocialProvider;
use App\Livewire\Home;
use App\Livewire\Profile;
use App\Livewire\Passport\Home as PassportHome;
use App\Livewire\Passport\CreateClient as PassportCreateClient;
use App\Livewire\Security\Home as SecurityHome;
use App\Livewire\Security\TwoFactor as SecurityTwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/', Home::class)->name('home');
    Route::livewire('/profile', Profile::class)->name('profile');

    Route::prefix('/security')->group(function () {
        Route::livewire('/', SecurityHome::class)->name('security');
        Route::livewire('/two-factor', SecurityTwoFactor::class)
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication() &&
                        Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    []
                )
            )
            ->name('security.2fa');
    });

    Route::middleware(['verified', 'password.confirm'])->group(function () {
        Route::prefix('/passport')->group(function () {
            Route::livewire('/', PassportHome::class)->name('passport.home');
            Route::livewire('/create-client', PassportCreateClient::class)->name('passport.create.client');
        });
    });
});

Route::controller(SocialProvider::class)
    ->prefix('/socials')
    ->group(function () {
        Route::get('/google', 'google')->name('socials.google');
        Route::get('/github', 'github')->name('socials.github');

        Route::get('/{provider}/callback', 'callback');
    });

require __DIR__ . '/auth.php';
