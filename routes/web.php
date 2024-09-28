<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.public.home');

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'pages.app.dashboard')->middleware('verified')->name('dashboard');
    Route::view('user', 'pages.app.user')->name('user');
    Route::view('profile', 'pages.app.profile')->name('profile');
});

require __DIR__.'/auth.php';
