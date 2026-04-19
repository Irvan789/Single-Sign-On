<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureDefaults();

        $this->configurePassport();
        $this->configureTurnstile();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
                : null
        );
    }

    protected function configurePassport(): void
    {
        Passport::authorizationView('oauth.authorize');
        Passport::tokensCan([
            'user' => 'Retrive the user profile',
            'user:email' => 'Retrive the user email'
        ]);

        Passport::tokensExpireIn(CarbonInterval::hours(1));
        Passport::refreshTokensExpireIn(CarbonInterval::days(7));
    }

    protected function configureTurnstile(): void
    {
        Blade::directive('turnstile', function () {
            return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>';
        });
    }
}
