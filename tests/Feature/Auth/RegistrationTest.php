<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

describe('Registration Tests', function () {
    test('Registration page can be rendered', function () {
        $page = visit(route('register'));

        $page
            ->assertPresent('name')
            ->assertPresent('username')
            ->assertPresent('email')
            ->assertPresent('password')
            ->assertPresent('password_confirmation')
            ->assertPresent('captcha')
            ->assertPresent('button[data-action="register"]');

        $page->assertNoJavaScriptErrors();
    });

    test('User can register using the registration page', function () {
        $page = visit(route('register'));

        $page
            ->type('name', 'Test Account')
            ->type('username', 'test_account')
            ->type('email', 'test-email@mail.com')
            ->type('password', 'password')
            ->type('password_confirmation', 'password')
            ->wait(4)
            ->press('button[data-action="register"]');

        $page->assertUrlIs(route('home'));
    });
});
