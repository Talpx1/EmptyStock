<?php

declare(strict_types=1);
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

use function Pest\Laravel\assertDatabaseHas;

describe('accessors and mutators', function () {});

describe('database constraints', function () {
    test('first_name is required', function () {
        User::factory()->create(['first_name' => null]);
    })->throws(QueryException::class, 'first_name', 23000);

    test('last_name is required', function () {
        User::factory()->create(['last_name' => null]);
    })->throws(QueryException::class, 'last_name', 23000);

    test('email is required', function () {
        User::factory()->create(['email' => null]);
    })->throws(QueryException::class, 'email', 23000);

    test('email must be unique', function () {
        User::factory()->create(['email' => 'test@test.test']);
        User::factory()->create(['email' => 'test@test.test']);
    })->throws(QueryException::class, 'email', 23000);

    test('email_verified_at is nullable', function () {
        User::factory()->create(['email_verified_at' => null]);
        assertDatabaseHas(User::class, ['email_verified_at' => null]);
    });

    test('password is required', function () {
        User::factory()->create(['password' => null]);
    })->throws(QueryException::class, 'password', 23000);
});

describe('relations', function () {
    test('has many profiles', function () {
        $user = User::factory()->create();

        $user_profiles = Profile::factory()->count(3)->for($user)->create();
        $other_user_profiles = Profile::factory()->count(4)->for(User::factory())->create();

        expect($user->profiles)->toHaveCount(3);
        expect($user->profiles)->toBeInstanceOf(Collection::class);
        expect($user->profiles)->toContainOnlyInstancesOf(Profile::class);
        expect($user->profiles)->toContain($user_profiles);
        expect($user->profiles)->not->toContain($other_user_profiles);
    });
});
