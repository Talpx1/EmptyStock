<?php

declare(strict_types=1);

use App\Models\Profile;
use App\Rules\Username;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

test('username must contain at least 2 letters', function () {
    Validator::validate(['username' => 'a12345'], ['username' => new Username]);
})->throws(ValidationException::class, 'The username must contain at least 2 letters.');

test('username may only contain letters numbers and underscores', function () {
    Validator::validate(['username' => 'username!'], ['username' => new Username]);
})->throws(ValidationException::class, 'The username may only contain letters, numbers, and underscores.');

test('username must not be reserved', function () {
    Validator::validate(['username' => 'admin'], ['username' => new Username]);
})->throws(ValidationException::class, 'The username is reserved.');

test('username must not be already in use', function () {
    Profile::factory()->create(['username' => 'already_in_use']);

    Validator::validate(['username' => 'already_in_use'], ['username' => new Username]);
})->throws(ValidationException::class, 'The username has already been taken.');
