<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
});

describe('Two Factor Challenge Tests', function () {
    test('Two factor challenge redirects to login when not authenticated', function () {
        $page = visit(route('two-factor.challenge'));

        $page->assertUrlIs(route('login'));
    });

    test('Two factor challenge page can be rendered', function () {
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true
        ]);

        $user = User::factory()->withTwoFactor()->create();
        $page = visit(route('login'));

        $page
            ->type('email', $user->email)
            ->type('password', 'password')
            ->check('remember')
            ->wait(4)
            ->press('button[data-action="login"]');

        $page->assertUrlIs(route('two-factor.challenge'));
    });
});
