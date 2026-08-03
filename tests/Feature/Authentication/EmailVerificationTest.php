<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::emailVerification());
});

describe('Email Verification Tests', function () {
    test('Email verification page can be rendered', function () {
        $user = User::factory()->unverified()->create();

        actingAs($user);

        $page = visit(route('verification.notice'));

        $page->assertUrlIs(route('verification.notice'));
    });

    test('User email can\'t be verified with invalid hash', function () {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1('wrong-email@mail.com'),
        ]);

        actingAs($user)->get($verificationUrl);

        expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    });

    test('User email can be verified with valid hash', function () {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $response = actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
        $response->assertRedirect(route('home', absolute: false).'?verified=1');
    });
});
