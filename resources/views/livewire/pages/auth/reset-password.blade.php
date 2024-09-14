<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state('token')->locked();

state([
    'email' => fn() => request()->string('email')->value(),
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'token' => ['required'],
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$resetPassword = function () {
    $this->validate();

    // Here we will attempt to reset the user's password. If it is successful we
    // will update the password on an actual user model and persist it to the
    // database. Otherwise we will parse the error and return the response.
    $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
        $user
            ->forceFill([
                'password' => Hash::make($this->password),
                'remember_token' => Str::random(60),
            ])
            ->save();

        event(new PasswordReset($user));
    });

    // If the password was successfully reset, we will redirect the user back to
    // the application's home authenticated view. If there is an error we can
    // redirect them back to where they came from with their error message.
    if ($status != Password::PASSWORD_RESET) {
        $this->addError('email', __($status));

        return;
    }

    Session::flash('status', __($status));

    $this->redirectRoute('login', navigate: true);
};

?>

<div>
    <x-form wire:submit="resetPassword">
        {{-- Email Address --}}
        <x-input :label="__('Email')" wire:model="email" type="email" name="email" required autofocus
            autocomplete="username" />

        {{-- Password --}}
        <x-input :label="__('Password')" wire:model="password" type="password" name="password" required
            autocomplete="new-password" />

        {{-- Confirm Password --}}
        <x-input :label="__('Confirm Password')" wire:model="password_confirmation" type="password" name="password_confirmation"
            required autocomplete="new-password" />

        <div class="flex items-center justify-end mt-4">
            <x-button class="btn-primary" :label="__('Reset Password')" />
        </div>
    </x-form>
</div>
