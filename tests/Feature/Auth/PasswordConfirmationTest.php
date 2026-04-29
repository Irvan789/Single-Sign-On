<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Password Confirmation Tests', function () {
    test('Password confirmation page can be rendered', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page->wait(2)
            ->assertPresent('password')
            ->assertPresent('button[data-action="confirm"]');

        $page->assertNoJavaScriptErrors();
    });

    test('Use can\'t entering secure page with invalid password', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page
            ->wait(2)
            ->type('password', 'wrong-password')
            ->pressAndWaitFor('button[data-action="confirm"]', 2);

        $page->assertUrlIs(route('password.confirm'));
    });

    test('Use can entering secure page with valid password', function () {
        $user = User::factory()->create();

        actingAs($user);

        $page = visit(route('password.confirm'));
        $page
            ->wait(2)
            ->type('password', 'password')
            ->pressAndWaitFor('button[data-action="confirm"]', 2);

        $page->assertUrlIs(route('home'));
    });
});
