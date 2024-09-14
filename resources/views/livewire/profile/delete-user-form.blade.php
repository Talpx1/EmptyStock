<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state(['password' => '', 'modal' => false]);

rules(['password' => ['required', 'string', 'current_password']]);

$deleteUser = function (Logout $logout) {
    $this->validate();

    tap(Auth::user(), $logout(...))->delete();

    $this->redirect('/', navigate: true);
};

$toggleModal = fn() => ($this->modal = !$this->modal);

?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-button class="btn-danger" wire:click="toggleModal" :label="__('Delete Account')" />

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

            <div class="mt-6 flex justify-end">
                <x-button wire:click="toggleModal" :label="__('Cancel')" />
                <x-button class="btn-danger" wire:click="toggleModal" :label="__('Delete Account')" />
            </div>
        </x-form>
    </x-modal>
</section>
