<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->laravel();
//arch()->preset()->strict();
arch()->preset()->security()->ignoring("Database\Factories");

arch('http helpers')
    ->expect(['session', 'auth', 'request'])
    ->toOnlyBeUsedIn([
        'App\Http',
        'App\Rules',
        'App\Livewire',
    ]);
