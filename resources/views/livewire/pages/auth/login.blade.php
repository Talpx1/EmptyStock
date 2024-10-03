<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('app.dashboard', absolute: false), navigate: true);
};

?>

<div>
    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-form wire:submit="login" no-separator class="gap-6 px-6">
        {{-- Email Address --}}
        <x-input :label="__('Email')" wire:model="form.email" icon="o-envelope" type="email" name="email" required
            autofocus autocomplete="username" />


        {{-- Password --}}
        <x-input :label="__('Password')" wire:model="form.password" icon="o-lock-closed" name="password" type="password"
            required autocomplete="current-password" />

        {{-- Remember Me --}}
        <div class="block mt-4">
            <x-checkbox :label="__('Remember me')" wire:model="form.remember" name="remember" />
        </div>

        <x-slot:actions>
            <div class="flex flex-col gap-6 w-full">
                <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                    @if (Route::has('password.request'))
                        <a class="order-2 sm:order-1 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button type="submit" class="btn-primary order-1 sm:order-2 w-full sm:w-auto"
                        :label="__('Log in')" />
                </div>

                <hr>

                <a class="underline text-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('register') }}" wire:navigate>
                    {{ __('New here? Sign up!') }}
                </a>
            </div>
        </x-slot:actions>
    </x-form>
</div>
