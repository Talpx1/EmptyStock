<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->middleware('verified')->name('dashboard');
    Route::view('user', 'user')->name('user');
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
