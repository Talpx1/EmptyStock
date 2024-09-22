<?php

declare(strict_types=1);

use App\Models\Traits\LogsAllDirtyChanges;

arch('all models log activity')
    ->expect('App\Models')
    ->toUseTrait(LogsAllDirtyChanges::class)
    ->ignoring('App\Models\Traits');

arch('app model traits are traits')->expect('App\Models\Traits')->toBeTraits();
