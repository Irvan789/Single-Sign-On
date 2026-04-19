<?php

use App\Livewire\Auth\Login;
use App\Models\User;

describe('Livewire Testing', function () {
    test('Authenticate user with invalid email credentials', function () {
        $response = Livewire::test(Login::class)
            ->set('email', 'test_example.com')
            ->set('password', 'password')
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('login');

        $response->assertHasErrors('email')->assertDispatched('notifications');
    });

    test('Authenticate user with password length less than 8', function () {
        $user = User::factory()->create();

        $response = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'pass')
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('login');

        $response->assertHasErrors('password')->assertDispatched('notifications');
    });

    test('Authenticate user with invalid password credentials', function () {
        $user = User::factory()->create();

        $response = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('login');

        $response->assertHasErrors('email')->assertDispatched('notifications');
    });

    test('Authenticate user with valid credentials', function () {
        $user = User::factory()->create();

        $response = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('captcha', 'XXXX.DUMMY.TOKEN.XXXX')
            ->call('login');

        $response->assertHasNoErrors()->assertRedirect(route('home'));
    });
});

describe('Browser Testing', function () {
    test('Login page can be rendered', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $page->screenshot(fullPage: true, filename: 'Login_01');

        $page
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=remember]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Login page can be accessible from register page', function () {
        $page = visit(route('register'));
        $page->resize(1536, 730)->wait(3);

        $page->click('Login')->wait(5);
        $page->screenshot(fullPage: true, filename: 'Login_02');

        $page
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=remember]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Login page can be accessible from forgot password page', function () {
        $page = visit(route('password.request'));
        $page->resize(1536, 730)->wait(3);

        $page->click('Login')->wait(5);
        $page->screenshot(fullPage: true, filename: 'Login_03');

        $page
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=remember]')
            ->assertPresent('input[name=cf-turnstile-response]')
            ->assertNoJavaScriptErrors();
    });

    test('Authenticate user with invalid email', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=email]', 'wrong-email@example.com')
            ->fill('input[name=password]', 'password')
            ->check('remember')
            ->click('Login')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Login_04');

        $page->assertUrlIs(route('login'));
    });

    test('Authenticate user with password length less than 8', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=email]', $user->email)
            ->fill('input[name=password]', 'pass')
            ->check('remember')
            ->click('Login')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Login_05');

        $page->assertUrlIs(route('login'));
    });

    test('Authenticate user with invalid credentials', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=email]', $user->email)
            ->fill('input[name=password]', 'wrong-password')
            ->check('remember')
            ->click('Login')
            ->wait(2)
            ->screenshot(fullPage: true, filename: 'Login_06');

        $page->assertUrlIs(route('login'));
    });

    test('Authenticate user with valid credentials', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=email]', $user->email)
            ->fill('input[name=password]', 'password')
            ->check('remember')
            ->click('Login')
            ->wait(3)
            ->screenshot(fullPage: true, filename: 'Login_07');

        $page->assertUrlIs(route('home'));
    });

    test('Authenticate user with valid credentials & make sure user can\'t access login page', function () {
        $page = visit(route('login'));
        $page->resize(1536, 730)->wait(5);

        $user = User::factory()->create();

        $page
            ->fill('input[name=email]', $user->email)
            ->fill('input[name=password]', 'password')
            ->check('remember')
            ->click('Login')
            ->wait(3);

        $page->navigate(route('login'))->wait(3);
        $page->screenshot(fullPage: true, filename: 'Login_08');

        $page->assertUrlIs(route('home'));
    });
});
