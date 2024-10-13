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
use function Livewire\Volt\mount;
use function Livewire\Volt\with;

//TODO: test

uses([Toast::class]);

layout('layouts.app');

state([
    'shop' => fn() => type(auth()->user())
        ->as(User::class)
        ->active_profile->load('shop')->shop,
])->locked();

state(['name', 'slogan', 'description', 'vat_number', 'iban']);

mount(function () {
    $this->name = $this->shop->name;
    $this->slogan = $this->shop->slogan;
    $this->description = $this->shop->description;
    $this->vat_number = $this->shop->vat_number;
    $this->iban = $this->shop->iban;
});

rules([
    'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'name')],
    'slogan' => ['nullable', 'string', 'min:2', 'max:255'],
    'description' => ['nullable', 'string', 'max:255'],
    'vat_number' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'vat_number')],
    'iban' => ['required', 'string', 'min:2', 'max:255', Rule::unique(Shop::class, 'iban')],
]);

$updateShop = function () {
    $validated = $this->validate();

    $this->shop->update($validated);

    $this->success(__("All done! {$validated['name']} has been updated!"));
};

?>

<div>
    <x-app.app-card>
        <x-slot:heading>{{ __('Edit your shop') }}</x-slot:heading>
        <x-slot:description>{{ __('Feel like refreshing your shop?') }}</x-slot:description>

        <x-form wire:submit="updateShop" class="mt-6 flex flex-col gap-y-6" no-separator>

            {{-- //TODO: extract form fields in own component, reuse in shop create --}}
            <x-input icon="o-building-storefront" :label="__('Name')" wire:model="name" name="name" type="text"
                min="2" max="255" :placeholder="__('The name of your shop')" required />

            <x-input icon="o-megaphone" :label="__('Slogan')" wire:model="slogan" name="slogan" type="text" min="2"
                max="255" :placeholder="__('An short and exciting phrase, representing your shop')" />

            <x-input :label="__('VAT number')" wire:model="vat_number" name="vat_number" type="text" min="2"
                max="255" :placeholder="__('The VAT number of the company that sells the products')" required />

            <x-input icon="o-banknotes" :label="__('IBAN')" wire:model="iban" name="iban" type="text" min="2"
                max="255" :placeholder="__('The IBAN of the account where to transfer the money of your sales')" required />

            <x-textarea :label="__('Description')" wire:model="description" :placeholder="__('Tell the story of your shop!')" rows="5" />

            <x-slot:actions>
                <div class="w-full justify-start">
                    <x-button class="btn-primary w-full sm:w-auto" :label="__('Update my Shop')" type="submit" />
                </div>
            </x-slot:actions>
        </x-form>

    </x-app.app-card>
</div>
