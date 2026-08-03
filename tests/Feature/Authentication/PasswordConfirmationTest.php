<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Password Confirmation Tests', function () {
    test('Password confirmation page can be rendered', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page
            ->assertPresent('password')
            ->assertPresent('button[data-test="confirm"]');

        $page->assertNoJavaScriptErrors();
    });

    test('Use can\'t entering secure page with invalid password', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page
            ->type('password', 'wrong-password')
            ->click('@confirm');

        $page->assertUrlIs(route('password.confirm'));
    });

    test('Use can entering secure page with valid password', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page
            ->type('password', 'password')
            ->click('@confirm');

        $page->assertUrlIs(route('home'));
    });
});
