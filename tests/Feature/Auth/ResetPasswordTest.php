<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

describe('Livewire Testing', function () {
    test('User doing reset password with invalid token', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
                ->set('email', $user->email)
                ->set('password', 'password')
                ->set('password_confirmation', 'password')
                ->call('resetPassword');

            $response->assertHasErrors('email');

            return true;
        });
    });

    test('User doing reset password with invalid email', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = Livewire::test(ResetPassword::class, ['token' => $notification->token])
                ->set('email', Str::replace('@', '_', $user->email))
                ->set('password', 'password')
                ->set('password_confirmation', 'password')
                ->call('resetPassword');

            $response->assertHasErrors('email');

            return true;
        });
    });

    test('User doing reset password with password length less than 8', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = Livewire::test(ResetPassword::class, ['token' => $notification->token])
                ->set('email', $user->email)
                ->set('password', 'pass')
                ->set('password_confirmation', 'pass')
                ->call('resetPassword');

            $response->assertHasErrors('password');

            return true;
        });
    });

    test('User doing reset password with password confirmation not same as password', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = Livewire::test(ResetPassword::class, ['token' => $notification->token])
                ->set('email', $user->email)
                ->set('password', 'password')
                ->set('password_confirmation', 'wrong-password')
                ->call('resetPassword');

            $response->assertHasErrors('password');

            return true;
        });
    });

    test('User doing reset password with valid token', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = Livewire::test(ResetPassword::class, ['token' => $notification->token])
                ->set('email', $user->email)
                ->set('password', 'password')
                ->set('password_confirmation', 'password')
                ->call('resetPassword');

            $response->assertHasNoErrors()->assertRedirect(route('login'));

            return true;
        });
    });
});

describe('Browser Testing', function () {
    test('Reset password page can be rendered', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => $user->email
                ])
            );
            $page->resize(1536, 730)->wait(3)->screenshot(fullPage: true, filename: 'Reset_01');

            $page
                ->assertPresent('input[name=password]')
                ->assertPresent('input[name=password_confirmation]')
                ->assertNoJavaScriptErrors();

            return true;
        });
    });

    test('User doing reset password with invalid email', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token
                ])
            );
            $page->resize(1536, 730)->wait(3);

            $page
                ->fill('input[name=password]', 'password')
                ->fill('input[name=password_confirmation]', 'password')
                ->click('Reset Password')
                ->wait(3)
                ->screenshot(fullPage: true, filename: 'Reset_02');

            $page->assertUrlIs(
                route('password.reset', [
                    'token' => $notification->token
                ])
            );

            return true;
        });
    });

    test('User doing reset password with password length less than 8', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => $user->email
                ])
            );
            $page->resize(1536, 730)->wait(3);

            $page
                ->fill('input[name=password]', 'pass')
                ->fill('input[name=password_confirmation]', 'pass')
                ->click('Reset Password')
                ->wait(3)
                ->screenshot(fullPage: true, filename: 'Reset_03');

            $page->assertUrlIs(
                route('password.reset', [
                    'token' => $notification->token
                ])
            );

            return true;
        });
    });

    test('User doing reset password with password confirmation not same as password', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => $user->email
                ])
            );
            $page->resize(1536, 730)->wait(3);

            $page
                ->fill('input[name=password]', 'password')
                ->fill('input[name=password_confirmation]', 'wrong-password')
                ->click('Reset Password')
                ->wait(3)
                ->screenshot(fullPage: true, filename: 'Reset_04');

            $page->assertUrlIs(
                route('password.reset', [
                    'token' => $notification->token
                ])
            );

            return true;
        });
    });

    test('User doing reset password with valid token', function () {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('sendPasswordResetLink');

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => $user->email
                ])
            );
            $page->resize(1536, 730)->wait(3);

            $page
                ->fill('input[name=password]', 'password')
                ->fill('input[name=password_confirmation]', 'password')
                ->click('Reset Password')
                ->wait(3)
                ->screenshot(fullPage: true, filename: 'Reset_05');

            $page->assertUrlIs(route('login'));

            return true;
        });
    });
});
