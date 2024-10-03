<?php

use App\Models\User;
use App\Rules\Username;
use Mary\Traits\Toast;

use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

state([
    'username' => fn() => type(auth()->user())->as(User::class)->active_profile->username,
]);

$updateProfileInformation = function () {
    $profile = type(auth()->user())->as(User::class)->active_profile;

    $validated = $this->validate([
        'username' => ['required', 'string', 'min:3', 'max:255', 'lowercase', new Username($profile)],
    ]);

    $profile->update($validated);

    $this->success(__('Profile updated successfully!'));

    //TODO: update session in real time / refresh component (sidebar still shows old username)
};

?>

<x-form wire:submit="updateProfileInformation" class="mt-6 flex flex-col gap-y-6" no-separator>
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
