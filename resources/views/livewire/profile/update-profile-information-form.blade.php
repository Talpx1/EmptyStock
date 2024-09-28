<?php

use App\Models\User;
use App\Rules\Username;
use Mary\Traits\Toast;

use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

state([
    'username' => fn () => type(auth()->user())->as(User::class)->active_profile->username,
]);

$updateProfileInformation = function () {
    $profile = type(auth()->user())->as(User::class)->active_profile;

    $validated = $this->validate([
        'username' => ['required', 'string', 'min:3', 'max:255', 'lowercase', new Username($profile)],
    ]);

    $profile->update($validated);

    $this->success(__('Profile updated successfully!'));
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update profile information for @:username", ['username'=>$username]) }}
        </p>
    </header>

    <x-form wire:submit="updateProfileInformation" class="mt-6 flex- flex-col gap-y-6" no-separator>
        <div>
            <x-input icon="o-at-symbol" :label="__('Username')" wire:model="username" name="username" type="text" required
                autocomplete="username" />
        </div>

        <x-slot:actions>
            <div class="w-full justify-start">
                <x-button class="btn-primary w-full sm:w-auto" :label="__('Save')" type="submit" />
            </div>
        </x-slot:actions>
    </x-form>
</section>
