<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state(['password' => '', 'modal' => false]);

rules(['password' => ['required', 'string', 'current_password']]);

$toggleModal = fn() => ($this->modal = !$this->modal);

$deleteUser = function (Logout $logout) {
    $this->validate();

    tap(Auth::user(), $logout(...))->delete();

    $this->modal = false;

    $this->redirect('/', navigate: true);
};

?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your user account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <x-alert :title="__('Important notice!')" :description="__(
            'This action will delete all your profiles! If you want to only delete one of your profiles, do so in the profile settings.',
        )" icon="o-exclamation-triangle" class="alert-warning mt-3" />

        <x-alert :title="__('Your shop may get deleted!')" :description="__(
            'If you are the owner of one or more shops, those will be deleted as well. Please transfer ownership in the shop settings if you don\'t want your shop to be deleted.',
        )" icon="o-exclamation-triangle" class="alert-warning mt-3" />
    </header>

    <x-button class="btn-error" wire:click="toggleModal" :label="__('Delete Account')" />

    <x-modal wire:model='modal'>
        <x-form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input :label="__('Password')" wire:model="password" name="password" type="password" :placeholder="__('Password')" />
            </div>

            <div class="mt-6 gap-6 flex justify-end">
                <x-button wire:click="toggleModal" :label="__('Cancel')" />
                <x-button class="btn-error" :label="__('Delete Account')" type="submit" />
            </div>
        </x-form>
    </x-modal>
</section>
