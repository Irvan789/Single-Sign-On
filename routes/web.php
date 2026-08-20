<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Pages\AccountSecurity\UserPassword as AccountUserPassword;
use App\Livewire\Pages\AccountSecurity\UserTwoFactor as AccountUserTwoFactor;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\OAuthPassport\CreateClients as OAuthCreateClients;
use App\Livewire\Pages\OAuthPassport\ManageClients as OAuthManageClients;
use App\Livewire\Pages\OAuthPassport\UpdateClients as OAuthUpdateClients;
use App\Livewire\Pages\Profile;
use App\Livewire\Pages\Users\ManageAccounts as ManageUserAccounts;
use App\Livewire\Pages\Users\UpdateAccounts as UpdateUserAccounts;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/', Home::class)->name('home');
    Route::livewire('/profile', Profile::class)->name('profile');

    Route::name('security.')->prefix('/security')->group(function () {
        Route::livewire('/', AccountUserPassword::class)->name('password');
        Route::livewire('/two-factor', AccountUserTwoFactor::class)
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication() &&
                        Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    []
                )
            )
            ->name('two-factor');
    });

    Route::middleware(['user.admin', 'verified', 'password.confirm'])->group(function () {
        Route::name('users.')->prefix('/users')->group(function () {
            Route::livewire('/', ManageUserAccounts::class)->name('manage.accounts');
            Route::livewire('/{id}', UpdateUserAccounts::class)->whereUuid('id')->name('update.accounts');
        });

        Route::name('oauth.')->prefix('/oauth/clients')->group(function () {
            Route::livewire('/', OAuthManageClients::class)->name('manage.clients');

            Route::livewire('/create', OAuthCreateClients::class)->name('create.clients');
            Route::livewire('/{id}', OAuthUpdateClients::class)->whereUuid('id')->name('update.clients');
        });
    });
});

Route::controller(SocialiteController::class)
    ->prefix('/socials')
    ->group(function () {
        Route::get('/{provider}', 'redirect')
            ->whereIn('provider', ['google', 'github'])
            ->name('socials.login');

        Route::get('/{provider}/callback', 'callback');
    });

require __DIR__.'/auth.php';
