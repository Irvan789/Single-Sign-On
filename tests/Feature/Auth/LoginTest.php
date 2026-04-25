<?php

describe('Login Testing', function () {
    test('Login page can be rendered', function () {
        $page = visit(route('login'));

        $page
            ->wait(5)
            ->assertPresent('input[name=email]')
            ->assertPresent('input[name=password]')
            ->assertPresent('input[name=remember]')
            ->assertPresent('input[name=captcha]')
            ->assertNoJavaScriptErrors();
    });
});
