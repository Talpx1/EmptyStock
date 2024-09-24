<?php

declare(strict_types=1);
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\QueryException;

describe('database constraints', function () {
    test('user_id is required', function () {
        Profile::factory()->create(['user_id' => null]);
    })->throws(QueryException::class, 'user_id', 23000);

    test('username is required', function () {
        Profile::factory()->create(['username' => null]);
    })->throws(QueryException::class, 'username', 23000);
});

describe('accessors and mutators', function () {});

describe('relations', function () {
    it('belongs to user', function () {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create();

        expect($profile->user)->toBeInstanceOf(User::class);
        expect($profile->user)->toBe($user);
    });
});
