<?php declare(strict_types=1);

use App\Models\Profile;
use App\Models\Shop;
use App\Models\User;
use App\Rules\Username;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use App\Actions\SetActiveProfile;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\uses;

uses([Toast::class]);

layout('layouts.app');

state(['name', 'slogan', 'description', 'vat_number', 'iban', 'username']);

rules([
    'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'name')],
    'slogan' => ['nullable', 'string', 'min:2', 'max:255'],
    'description' => ['nullable', 'string', 'max:255'],
    'username' => [Rule::requiredIf(request()->has('as_new_profile')), 'string', 'min:3', 'max:255', 'lowercase', new Username()],
    'vat_number' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'vat_number')],
    'iban' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'iban')],
]);

$storeShop = function () {
    $validated = $this->validate();

    DB::transaction(function () use ($validated) {
        $shop = Shop::create(Arr::except($validated, ['username']));

        if (!is_null($validated['username'])) {
            $profile = Profile::create([
                'user_id' => type(auth()->user())->as(User::class)->id,
                'username' => $validated['username'],
                'shop_id' => $shop->id,
            ]);

            SetActiveProfile::run($profile);

            return;
        }

        type(auth()->user())
            ->as(User::class)
            ->active_profile->update([
                'shop_id' => $shop->id,
            ]);
    });

    $this->success(__("Amazing! {$validated['name']} is now open!"));

    $this->redirect(route('app.profile'), navigate: true);
};

?>

<div>
    <x-app.app-card>
        <x-slot:heading>{{ __('Create your shop') }}</x-slot:heading>
        <x-slot:description>{{ __('Provide the following info to setup your shop and start publishing your products.') }}</x-slot:description>

        <x-form wire:submit="storeShop" class="mt-6 flex flex-col gap-y-6" no-separator>
            <x-input icon="o-building-storefront" :label="__('Name')" wire:model="name" name="name" type="text"
                min="2" max="255" :placeholder="__('The name of your shop')" required />

            <x-input icon="o-megaphone" :label="__('Slogan')" wire:model="slogan" name="slogan" type="text" min="2"
                max="255" :placeholder="__('An short and exciting phrase, representing your shop')" />

            <x-input :label="__('VAT number')" wire:model="vat_number" name="vat_number" type="text" min="2"
                max="255" :placeholder="__('The VAT number of the company that sells the products')" required />

            <x-input icon="o-banknotes" :label="__('IBAN')" wire:model="iban" name="iban" type="text" min="2"
                max="255" :placeholder="__('The IBAN of the account where to transfer the money of your sales')" required />

            <x-textarea label="Description" wire:model="description" placeholder="Tell the story of your shop!"
                rows="5" />

            @if (request()->has('as_new_profile'))
                <x-input :label="__('Username')" :placeholder="__('The username for your new shop-owner profile')" icon="o-at-symbol" wire:model="username" type="text"
                    required autocomplete="username" />
            @endif
            <x-slot:actions>
                <div class="w-full justify-start">
                    <x-button class="btn-primary w-full sm:w-auto" :label="__('Open my Shop')" type="submit" />
                </div>
            </x-slot:actions>
        </x-form>

    </x-app.app-card>
</div>
