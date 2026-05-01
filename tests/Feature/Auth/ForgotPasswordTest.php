<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());
});

describe('Forgot Password Tests', function () {
    test('Forgot Password page can be rendered', function () {
        $page = visit(route('password.request'));

        $page
            ->assertPresent('email')
            ->assertPresent('captcha')
            ->assertPresent('button[data-action="forgot"]');

        $page->assertNoJavaScriptErrors();
    });

    test('User doing reset password with invalid email', function () {
        $page = visit(route('password.request'));

        $page
            ->type('email', 'wrong-email@mail.com')
            ->wait(4)
            ->press('button[data-action="forgot"]');

        $page->assertUrlIs(route('password.request'));
    });

    test('User doing reset password with valid email', function () {
        $user = User::factory()->create();
        $page = visit(route('password.request'));

        $page
            ->type('email', $user->email)
            ->wait(4)
            ->press('button[data-action="forgot"]');

        $page->assertUrlIs(route('login'));
    });
});
