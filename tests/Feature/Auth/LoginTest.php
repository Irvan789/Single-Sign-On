<?php

use App\Models\User;

describe('Login Tests', function () {
    test('Login page can be rendered', function () {
        $page = visit(route('login'));

        $page
            ->wait(2)
            ->assertPresent('email')
            ->assertPresent('password')
            ->assertPresent('remember')
            ->assertPresent('captcha')
            ->assertPresent('button[data-action="login"]');

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
            ->pressAndWaitFor('button[data-action="login"]', 2);

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
            ->pressAndWaitFor('button[data-action="login"]', 2);

        $page->assertUrlIs(route('home'));
    });
});
