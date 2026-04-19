<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;

describe('Livewire Testing', function () {
    test('Email can be verified', function () {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email)
        ]);

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
        $response->assertRedirect(route('home'));
    });
});

describe('Browser Testing', function () {
    test('Email verification page can be rendered', function () {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $page = visit(route('verification.notice'));
        $page->resize(1536, 730)->wait(3)->screenshot(fullPage: true, filename: 'Reset_01');

        $page->assertUrlIs(route('verification.notice'));
    });

    test('User can click re-send verification email', function () {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $page = visit(route('verification.notice'));
        $page->resize(1536, 730)->wait(3);

        $page->click('Re-send Verification Email')->wait(1)->screenshot(fullPage: true, filename: 'Reset_02');

        $page->assertUrlIs(route('verification.notice'));
    });

    test('User can click logout', function () {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $page = visit(route('verification.notice'));
        $page->resize(1536, 730)->wait(3);

        $page->click('Logout')->wait(3)->screenshot(fullPage: true, filename: 'Reset_03');

        $page->assertUrlIs(route('login'));
    });

    test('Email can be verified', function () {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email)
        ]);

        $this->actingAs($user);

        $page = visit($verificationUrl);
        $page->resize(1536, 730)->wait(3);

        Event::assertDispatched(Verified::class);
        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

        $page->assertUrlIs(route('home'));
    });
});
