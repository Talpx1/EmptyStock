<?php

declare(strict_types=1);
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\QueryException;

describe('accessors and mutators', function () {});

describe('database constraints', function () {
    test('user_id is required', function () {
        Profile::factory()->create(['user_id' => null]);
    })->throws(QueryException::class, 'user_id', 23000);

    test('nickname is required', function () {
        Profile::factory()->create(['nickname' => null]);
    })->throws(QueryException::class, 'nickname', 23000);
});

describe('relations', function () {
    test('belongs to user', function () {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create();

        expect($profile->user)->toBeInstanceOf(User::class);
        expect($profile->user)->toBe($user);
    });
});
