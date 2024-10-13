<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('dashboard', 'pages.app.dashboard')->name('app.dashboard');

Route::view('user', 'pages.app.user')->name('app.user');
Route::view('profile', 'pages.app.profile')->name('app.profile');

Volt::route('shop/create', 'pages.shop.create')->name('app.shop.create');
Volt::route('shop/edit', 'pages.shop.edit')->name('app.shop.edit');

Volt::route('product/create', 'pages.product.create')->name('app.product.create');
Volt::route('product/{product}/edit', 'pages.product.create')->name('app.product.edit');
Volt::route('products', 'pages.product.index')->name('app.product.index');
