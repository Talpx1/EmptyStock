<?php

declare(strict_types=1);
use App\Actions\UserRegistered;
use App\Models\User;

it('sets first user profile as active', function () {
    $user = User::factory()->withProfile()->create();
    $profile = $user->profiles()->first();

    UserRegistered::run($user);

    expect($user->active_profile)->toBe($profile);
});

it('can be called statically', function () {})->todo();
