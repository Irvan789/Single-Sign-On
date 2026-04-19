<?php

use App\Livewire\Auth\Register;
use App\Models\User;

describe('Livewire Testing', function () {
    test('Register new user with same username', function () {
        $user = User::factory()->create();

        $response = Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('username', $user->username)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        $response->assertHasErrors('username')->assertDispatched('notifications');
    });

    test('Register new user with same email', function () {
        $user = User::factory()->create();

        $response = Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('username', 'u000001')
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        $response->assertHasErrors('email')->assertDispatched('notifications');
    });

    test('Register new user with password length less than 8', function () {
        $response = Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('username', 'u000001')
            ->set('email', 'test@example.com')
            ->set('password', 'pass')
            ->set('password_confirmation', 'pass')
            ->call('register');

        $response->assertHasErrors('password')->assertDispatched('notifications');
    });

    test('Register new user with password confirmation not same as password', function () {
        $response = Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('username', 'u000001')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'wrong-password')
            ->call('register');

        $response->assertHasErrors('password')->assertDispatched('notifications');
    });

    test('Register new user with valid credentials', function () {
        $response = Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('username', 'u000001')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('register');

        $response->assertHasNoErrors()->assertRedirect(route('home'));
    });
});

describe('Browser Testing', function () {
    test('Register page can be rendered', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);
        $page->screenshot(fullPage: true, filename: 'Register_01');

        $page
            ->assertPresent('input[name=name]')
            ->assertPresent('input[name=username]')
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=password_confirmation]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Register page can be accessible from login page', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(3);
        $page->click('Register')->wait(5);
        $page->screenshot(fullPage: true, filename: 'Register_02');

        $page
            ->assertPresent('input[name=name]')
            ->assertPresent('input[name=username]')
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=password_confirmation]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Register new user with same username', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', $user->username)
            ->fill('input[name=email]', 'test@example.com')
            ->fill('input[name=password]', 'password')
            ->fill('input[name=password_confirmation]', 'password')
            ->click('Register')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Register_03');

        $page->assertUrlIs(route('register'));
    });

    test('Register new user with same email', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', 'u000001')
            ->fill('input[name=email]', $user->email)
            ->fill('input[name=password]', 'password')
            ->fill('input[name=password_confirmation]', 'password')
            ->click('Register')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Register_04');

        $page->assertUrlIs(route('register'));
    });

    test('Register new user with password length less than 8', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', 'u000001')
            ->fill('input[name=email]', 'test@example.com')
            ->fill('input[name=password]', 'pass')
            ->fill('input[name=password_confirmation]', 'pass')
            ->click('Register')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Register_05');

        $page->assertUrlIs(route('register'));
    });

    test('Register new user with password confirmation not same as password', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', 'u000001')
            ->fill('input[name=email]', 'test@example.com')
            ->fill('input[name=password]', 'password')
            ->fill('input[name=password_confirmation]', 'wrong-password')
            ->click('Register')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Register_06');

        $page->assertUrlIs(route('register'));
    });

    test('Register new user with valid credentials', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', 'u000001')
            ->fill('input[name=email]', 'test@example.com')
            ->fill('input[name=password]', 'password')
            ->fill('input[name=password_confirmation]', 'password')
            ->click('Register')
            ->wait(3)
            ->screenshot(fullPage: true, filename: 'Register_07');

        $page->assertUrlIs(route('home'));
    });

    test('Register new user with valid credentials & make sure user can\'t access register page', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(5);

        $page
            ->fill('input[name=name]', 'Test Name')
            ->fill('input[name=username]', 'u000001')
            ->fill('input[name=email]', 'test@example.com')
            ->fill('input[name=password]', 'password')
            ->fill('input[name=password_confirmation]', 'password')
            ->click('Register')
            ->wait(1);

        $page->navigate(route('register'))->wait(3);
        $page->screenshot(fullPage: true, filename: 'Register_08');

        $page->assertUrlIs(route('home'));
    });
});
