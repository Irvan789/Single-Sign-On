<?php

use App\Models\User;

describe('Login Tests', function () {
    test('Login page can be rendered', function () {
        $page = visit(route('login'));

        $page
            ->assertPresent('email')
            ->assertPresent('password')
            ->assertPresent('remember')
            ->assertPresent('captcha')
            ->assertPresent('button[data-test="login"]');

        $page->assertNoJavaScriptErrors();
    });

    test('User can\'t authenticate with invalid password', function () {
        $user = User::factory()->create();

        $page = visit(route('login'));
        $page
            ->type('email', $user->email)
            ->type('password', 'wrong-password')
            ->check('remember')
            ->wait(4)
            ->click('@login');

        $page->assertUrlIs(route('login'));
    });

    test('User can authenticate using the login page', function () {
        $user = User::factory()->create();

        $page = visit(route('login'));
        $page
            ->type('email', $user->email)
            ->type('password', 'password')
            ->check('remember')
            ->wait(4)
            ->click('@login');

        $page->assertUrlIs(route('home'));
    });
});
