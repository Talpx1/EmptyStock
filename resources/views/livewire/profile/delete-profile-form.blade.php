<?php

use App\Livewire\Actions\Logout;
use App\Actions\SetActiveProfile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Mary\Traits\Toast;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

state(['password' => '', 'modal' => false]);

rules(['password' => ['required', 'string', 'current_password']]);

$toggleModal = fn() => ($this->modal = !$this->modal);

$deleteProfile = function () {
    $this->validate();

    $user = type(auth()->user())->as(User::class);
    $profile_count = $user->loadCount('profiles')->profiles_count;

    if ($profile_count <= 1) {
        $this->error(__("You can't delete your only profile. You may consider deleting your user account instead."));
        $this->modal = false;
        return;
    }

    $alt_profile = $user->inactive_profiles->first();
    $user->active_profile->delete();
    SetActiveProfile::run($alt_profile);

    $this->modal = false;

    $this->redirect(route('app.dashboard'), navigate: true);
};

?>

<section class="space-y-6">
    <x-button class="btn-error" wire:click="toggleModal" :label="__('Delete Profile')" />

    <x-modal wire:model='modal'>
        <x-form wire:submit="deleteProfile" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your profile?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your profile is deleted, all of its resources and data will be permanently deleted. This action can\'t be undone.') }}<br />
                {{ __('Please enter your password to confirm you would like to permanently delete your profile.') }}
            </p>

            <div class="mt-6">
                <x-input :label="__('Password')" wire:model="password" name="password" type="password" :placeholder="__('Password')" />
            </div>

            <div class="mt-6 gap-6 flex justify-end">
                <x-button wire:click="toggleModal" :label="__('Cancel')" />
                <x-button class="btn-error" :label="__('Delete Profile')" type="submit" />
            </div>
        </x-form>
    </x-modal>
</section>
