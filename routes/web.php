<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.public.home');

Route::middleware(['auth', 'verified'])->prefix('app')->group(function () {
    require __DIR__.'/app.php';
});

require __DIR__.'/auth.php';
