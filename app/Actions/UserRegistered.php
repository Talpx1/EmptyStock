<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

class UserRegistered {
    //TODO: test
    public function handle(User $user): void {
        /** @var \App\Models\Profile */
        $profile = $user->profiles()->first();

        (new SetActiveProfile)->handle($profile);
    }
}
