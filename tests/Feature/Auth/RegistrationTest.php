<?php

namespace Tests\Feature\Auth;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register', function () {
    $component = Volt::test('pages.auth.register')
        ->set('first_name', 'Test')
        ->set('last_name', 'User')
        ->set('email', 'test@gmail.com')
        ->set('username', 'testuser')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('password gets hashed', function () {
    $component = Volt::test('pages.auth.register')
        ->set('first_name', 'Test')
        ->set('last_name', 'User')
        ->set('email', 'test@gmail.com')
        ->set('username', 'testuser')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');
    $this->assertAuthenticated();

    assertDatabaseHas(User::class, [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@gmail.com',
    ]);

    assertDatabaseMissing(User::class, [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@gmail.com',
        'password' => 'password',
    ]);
});

test('profile gets created', function () {
    $component = Volt::test('pages.auth.register')
        ->set('first_name', 'Test')
        ->set('last_name', 'User')
        ->set('email', 'test@gmail.com')
        ->set('username', 'testuser')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');
    $this->assertAuthenticated();

    assertDatabaseHas(Profile::class, [
        'user_id' => auth()->user()->id,
        'username' => 'testuser',
    ]);
});

describe('validation', function () {
    test('first_name is required', function () {
        Volt::test('pages.auth.register')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['first_name' => ['required']]);
    });

    test('first_name must be at most 255 chars long', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', Str::random(256))
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['first_name' => ['max:255']]);
    });

    test('first_name must be string', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 123)
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['first_name' => ['string']]);
    });

    test('last_name is required', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['last_name' => ['required']]);
    });

    test('last_name must be at most 255 chars long', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', Str::random(256))
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['last_name' => ['max:255']]);
    });

    test('last_name must be string', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 123)
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['last_name' => ['string']]);
    });

    test('username is required', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username' => ['required']]);
    });

    test('username must be string', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 123)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username' => ['string']]);
    });

    test('username must be at least 3 chars long', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'ab')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username' => ['min:3']]);
    });

    test('username must be at most 255 chars long', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', Str::random(256))
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username' => ['max:255']]);
    });

    test('username must be lowercase', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'TESTUSER')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username' => ['lowercase']]);
    });

    test('username must be valid', function (string $username) {
        //covers the "new Username" rule -> tested in isolation in Unit/Rules/UsernameTest
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', $username)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['username']);
    })->with('invalid_usernames');

    test('email is required', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('first_name', 'User')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['required']]);
    });

    test('email must be at most 255 chars long', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', Str::random(256).'@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['max:255']]);
    });

    test('email must be string', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 123)
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['string']]);
    });

    test('email must be lowercase', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'TEST@EXAMPLE.COM')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['lowercase']]);
    });

    test('email must be unique', function () {
        User::factory()->create(['email' => 'test@gmail.com']);

        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['unique:'.User::class]]);
    });

    test('email must be of valid domain when app is in production', function (string $email) {
        app()->detectEnvironment(fn () => 'production');

        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', $email)
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => ['email:rfc,dns']]);
    })->with(['a@a.com', 'b@b.com', 'a@123.test', '10mail.com', '10mail.org', '10mail.tk', '10mail.xyz', '10minmail.de', '10minut.com.pl', '10minut.xyz', '10minutemail.be', '10minutemail.cf', '10minutemail.co.uk', '10minutemail.co.za', '10minutemail.com', '10minutemail.de', '10minutesmail.com', '10minutesmail.fr', '10minutmail.pl', 'myzx.com', 'mzico.com', 'n1nja.org', 'zfymail.com', 'zhcne.com', 'zippymail.info']);

    test('email can be of fake domain if app is not in production', function (string $email) {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', $email)
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertOk();

        assertDatabaseHas(User::class, ['email' => $email]);
    })->with(['a@a.com', 'b@b.com', 'a@123.test']);

    test('password is required', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('first_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['password' => ['required']]);
    });

    test('password must be string', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 123)
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['password' => ['string']]);
    });

    test('password must be confirmed', function () {
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', 'password')
            ->call('register')
            ->assertHasErrors(['password' => ['confirmed']]);
    });

    test('password must be secure', function (string $password) {
        // PASSWORD RULES
        // Password::min(8)
        //         ->letters()
        //         ->mixedCase()
        //         ->numbers()
        //         ->symbols()
        //         ->uncompromised();
        Volt::test('pages.auth.register')
            ->set('first_name', 'Test')
            ->set('last_name', 'User')
            ->set('email', 'test@gmail.com')
            ->set('username', 'testuser')
            ->set('password', $password)
            ->call('register')
            ->assertHasErrors(['password']);
    })->with(['cW!jo23', '37189@!312', 'cwwa!jo23', 'cwwa!joWW', 'cwwa3223joWW', 'Password123!']);
});
