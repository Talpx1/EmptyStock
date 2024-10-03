<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

state([
    'first_name' => fn() => auth()->user()->first_name,
    'last_name' => fn() => auth()->user()->last_name,
    'email' => fn() => auth()->user()->email,
]);

$updateUserInformation = function () {
    $user = Auth::user();

    $validated = $this->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
    ]);

    $user->fill($validated);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    $this->success(__('User updated'));

    $this->dispatch('user-info-updated', id: $user->id);
};

$sendVerification = function () {
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('app.dashboard', absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('User Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your user account's information. Those are the one shared between your profiles.") }}
        </p>
    </header>

    <x-form wire:submit="updateUserInformation" class="mt-6 flex- flex-col gap-y-6" no-separator>
        <div class="contents sm:flex flex-col sm:flex-row gap-6">
            <div class="grow">
                <x-input :label="__('First name')" wire:model="first_name" name="first_name" type="text" required autofocus
                    autocomplete="first-name" />
            </div>
            <div class="grow">
                <x-input :label="__('Last name')" wire:model="last_name" name="last_name" type="text" required
                    autocomplete="last-name" />
            </div>
        </div>

        <div>
            <x-input :label="__('Login email')" wire:model="email" name="email" type="email" required
                autocomplete="username" />

            @if (auth()->user() instanceof MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <x-button wire:click.prevent="sendVerification" :label="__('Click here to re-send the verification email.')" />
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <x-slot:actions>
            <div class="w-full justify-start">
                <x-button class="btn-primary w-full sm:w-auto" :label="__('Save')" type="submit" />
            </div>
        </x-slot:actions>
    </x-form>
</section>
