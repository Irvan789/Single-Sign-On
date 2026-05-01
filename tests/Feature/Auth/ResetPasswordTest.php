<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;

use function Pest\Laravel\post;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());
});

describe('Reset Password Tests', function () {
    test('Reset Password page can be rendered', function () {
        $page = visit(
            route('password.reset', [
                'token' => 'test-token',
                'email' => 'test-email@mail.com'
            ])
        );

        $page
            ->assertPresent('password')
            ->assertPresent('password_confirmation')
            ->assertPresent('button[data-action="reset"]');

        $page->assertNoJavaScriptErrors();
    });

    test('User can\'t reset password with invalid email', function () {
        Notification::fake();

        $user = User::factory()->create();

        post(route('password.email'), [
            'email' => $user->email,
            'captcha' => 'XXXX.DUMMY.TOKEN.XXXX'
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => 'wrong-email@mail.com'
                ])
            );

            $page
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('button[data-action="reset"]');

            $page->assertUrlIs(
                route('password.reset', [
                    'token' => $notification->token
                ])
            );

            return true;
        });
    });

    test('User can reset password and logged in', function () {
        Notification::fake();

        $user = User::factory()->create();

        post(route('password.email'), [
            'email' => $user->email,
            'captcha' => 'XXXX.DUMMY.TOKEN.XXXX'
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (
            $user
        ) {
            $page = visit(
                route('password.reset', [
                    'token' => $notification->token,
                    'email' => $user->email
                ])
            );

            $page
                ->type('password', 'new-password')
                ->type('password_confirmation', 'new-password')
                ->press('button[data-action="reset"]');

            $page
                ->type('email', $user->email)
                ->type('password', 'new-password')
                ->check('remember')
                ->wait(4)
                ->press('button[data-action="login"]');

            $page->assertUrlIs(route('home'));

            return true;
        });
    });
});
