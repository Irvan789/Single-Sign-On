<?php

use App\Livewire\Auth\ForgotPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

describe('Livewire Testing', function () {
    test('Reset password link can be requested', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPassword::class);
    });
});

describe('Browser Testing', function () {
    test('Forgot password page can be rendered', function () {
        $page = visit(route('password.request'));
        $page->resize(1536, 730)->wait(5);

        $page->screenshot(fullPage: true, filename: 'Forgot_01');

        $page
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Forgot password page can be accessible from login page', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(3);

        $page->click('Forgot Password?')->wait(5);
        $page->screenshot(fullPage: true, filename: 'Forgot_02');

        $page
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Request reset password link with valid email', function () {
        $page = visit(route('password.request'));
        $page->resize(1536, 730)->wait(5);

        $page
            ->fill('input[name=email]', 'test@example.com')
            ->click('Send Password Reset Link')
            ->wait(3)
            ->screenshot(fullPage: true, filename: 'Forgot_03');

        $page->assertUrlIs(route('password.request'));
    });
});
