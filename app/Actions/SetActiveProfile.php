<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;
use Illuminate\Support\Facades\Session;
use Lorisleiva\Actions\Concerns\AsAction;

class SetActiveProfile {
    use AsAction;

    public function handle(Profile $profile): void {
        Session::put(Profile::ACTIVE_PROFILE_SESSION_KEY, $profile);
    }
}
