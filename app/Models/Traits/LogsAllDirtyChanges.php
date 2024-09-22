<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsAllDirtyChanges {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
