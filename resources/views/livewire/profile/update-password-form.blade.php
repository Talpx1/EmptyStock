<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Mary\Traits\Toast;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

state([
    'current_password' => '',
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'current_password' => ['required', 'string', 'current_password'],
    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
]);

$updatePassword = function () {
    try {
        $validated = $this->validate();
    } catch (ValidationException $e) {
        $this->reset('current_password', 'password', 'password_confirmation');

        throw $e;
    }

    Auth::user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    $this->reset('current_password', 'password', 'password_confirmation');

    $this->success(__('Saved.'));

    $this->dispatch('password-updated');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <x-input :label="__('Current Password')" wire:model="current_password" name="current_password" type="password"
            autocomplete="current-password" />

        <x-input :label="__('New Password')" wire:model="password" name="password" type="password" autocomplete="new-password" />

        <x-input for="update_password_password_confirmation" :label="__('Confirm Password')" wire:model="password_confirmation"
            name="password_confirmation" type="password" autocomplete="new-password" />

        <div class="flex items-center gap-4">
            <x-button class="btn-primary" :label="__('Save')" />
        </div>
    </form>
</section>
