<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UserRegistered {
    use AsAction;

    public function handle(User $user): void {
        /** @var \App\Models\Profile */
        $profile = $user->profiles()->first();

        SetActiveProfile::run($profile);
    }
}
