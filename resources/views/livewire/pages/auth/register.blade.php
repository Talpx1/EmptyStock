<?php

use App\Models\User;
use App\Rules\UnauthorizedEmailProviders;
use App\Rules\Username;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Actions\UserRegistered;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'username' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'first_name' => ['required', 'string', 'max:255'],
    'last_name' => ['required', 'string', 'max:255'],
    'username' => ['required', 'string', 'min:3', 'max:255', 'lowercase', new Username()],
    'email' => ['required', 'string', 'lowercase', 'max:255', 'email' . (app()->isProduction() ? ':rfc,dns' : ''), 'unique:' . User::class, new UnauthorizedEmailProviders()],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    $user = DB::transaction(function () use ($validated) {
        $user = User::create(Arr::except($validated, 'username'));
        $user->profiles()->create(['username' => $validated['username']]);

        return $user;
    });

    (new UserRegistered())->handle($user);

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div>
    <x-form wire:submit="register" class="gap-8">
        <div class="sm:flex contents flex-row gap-4">
            {{-- First Name --}}
            <x-input :label="__('First Name')" :placeholder="__('Your first name')" icon="o-user" wire:model="first_name" autofocus required
                autocomplete="first-name" />

            {{-- Last Name --}}
            <x-input :label="__('Last Name')" :placeholder="__('Your last name')" icon="o-user" wire:model="last_name" required
                autocomplete="last-name" />
        </div>



        {{-- Email Address --}}
        <x-input :label="__('Email')" :placeholder="__('Your email')" icon="o-envelope" wire:model="email" type="email" required
            autocomplete="username" />

        {{-- username --}}
        <x-input :label="__('Username')" :placeholder="__('username')" icon="o-at-symbol" wire:model="username" type="text" required
            autocomplete="username" />

        {{-- Password --}}
        <x-input :label="__('Password')" wire:model="password" :placeholder="__('Something secure here')" icon="o-lock-closed" type="password" required
            autocomplete="new-password" />

        {{-- Confirm Password --}}
        <x-input :label="__('Confirm Password')" wire:model="password_confirmation" :placeholder="__('Type your password again')" icon="o-lock-closed"
            type="password" required autocomplete="new-password" />

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
            <a class="order-2 sm:order-1 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-button class="btn-primary order-1 sm:order-2 w-full sm:w-auto" :label="__('Register')" type="submit" />
        </div>
    </x-form>
</div>
