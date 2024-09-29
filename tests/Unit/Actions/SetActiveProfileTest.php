<?php

declare(strict_types=1);
use App\Actions\SetActiveProfile;
use App\Models\Profile;

it('puts passed profile in correct session key', function () {
    $profile = Profile::factory()->create();

    expect(session()->missing(Profile::ACTIVE_PROFILE_SESSION_KEY))->toBeTrue();

    SetActiveProfile::run($profile);

    expect(session(Profile::ACTIVE_PROFILE_SESSION_KEY))->toBe($profile);
});
