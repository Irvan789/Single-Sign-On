<?php

use App\Livewire\Auth\ConfirmPassword;
use App\Models\User;

describe('Livewire Testing', function () {
    test('Password length less than 8', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test(ConfirmPassword::class)->set('password', 'pass')->call('confirmPassword');

        $response->assertHasErrors(['password']);
    });

    test('Password is not confirmed with invalid password', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test(ConfirmPassword::class)->set('password', 'wrong-password')->call('confirmPassword');

        $response->assertHasErrors(['password']);
    });

    test('Password can be confirmed', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = Livewire::test(ConfirmPassword::class)->set('password', 'password')->call('confirmPassword');

        $response->assertHasNoErrors()->assertRedirect(route('home'));
    });
});

describe('Browser Testing', function () {
    test('Confirm password page can be rendered', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $page = visit(route('password.confirm'));
        $page->resize(1536, 730)->wait(3)->screenshot(fullPage: true, filename: 'Confirm_01');

        $page->assertUrlIs(route('password.confirm'));
    });

    test('User entering password length less than 8', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $page = visit(route('password.confirm'));
        $page->resize(1536, 730)->wait(3);

        $page
            ->fill('input[name=password]', 'pass')
            ->click('Confirm')
            ->wait(1)
            ->screenshot(fullPage: true, filename: 'Confirm_02');

        $page->assertUrlIs(route('password.confirm'));
    });

    test('User entering invalid password', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $page = visit(route('password.confirm'));
        $page->resize(1536, 730)->wait(3);

        $page
            ->fill('input[name=password]', 'wrong-password')
            ->click('Confirm')
            ->wait(1)
            ->screenshot(fullPage: true, filename: 'Confirm_03');

        $page->assertUrlIs(route('password.confirm'));
    });

    test('User entering valid password', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $page = visit(route('password.confirm'));
        $page->resize(1536, 730)->wait(3);

        $page
            ->fill('input[name=password]', 'password')
            ->click('Confirm')
            ->wait(3)
            ->screenshot(fullPage: true, filename: 'Confirm_04');

        $page->assertUrlIs(route('home'));
    });
});
