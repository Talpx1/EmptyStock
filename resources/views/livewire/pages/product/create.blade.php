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
use function Livewire\Volt\js;

//TODO: test

uses([Toast::class]);

layout('layouts.app');

state(['shop' => type(auth()->user())->as(User::class)->active_profile->shop])->locked();
state(['title', 'description', 'price', 'is_bundle' => false, 'pieces_per_bundle' => null, 'individually_sellable' => false]);

$resetPiecesPerBundle = js('!$wire.is_bundle && ($wire.pieces_per_bundle=null)');

rules([
    'title' => ['required', 'string', 'min:2', 'max:255'],
    'description' => ['required', 'string', 'min:2'],
    'price' => ['numeric', 'min:0.01', 'max:' . PHP_FLOAT_MAX],
    'is_bundle' => ['nullable', 'boolean'],
    'pieces_per_bundle' => ['nullable', 'required_if_accepted:is_bundle', 'integer', 'min:2', 'max:' . PHP_INT_MAX],
    'individually_sellable' => ['nullable', 'boolean'],
]);

$storeProduct = function () {
    $validated = $this->validate();

    $this->shop->products()->create(Arr::except($validated, 'is_bundle'));

    $this->success(__("Woo! {$validated['title']} has been created"));

    $this->redirect(route('app.profile'), navigate: true);
};

?>

<div>
    <x-app.app-card>
        <x-slot:heading>{{ __('Create new product for :shop', ['shop' => $shop->name]) }}</x-slot:heading>
        <x-slot:description>{{ __("It's time to add a new amazing product to your shop! Just fill the form down there, and publish your new best-seller.") }}</x-slot:description>

        <x-form wire:submit="storeProduct" class="mt-6 flex flex-col gap-y-6" no-separator>

            <x-input icon="o-tag" :label="__('Title')" wire:model="title" name="title" type="text" min="2"
                max="255" :placeholder="__('The best title a product has ever had')" required />

            <x-input icon="o-banknotes" :label="__('Price')" wire:model="price" name="price" type="number" min=".01"
                max="{{ PHP_FLOAT_MAX }}" step=".01" :placeholder="__('Good prices, good deals')" required />

            <x-textarea :label="__('Description')" wire:model="description" :placeholder="__('Convince everybody to buy this product. Tell the world everything about it')" rows="5" required />

            <x-checkbox @change="$wire.resetPiecesPerBundle" :label="__('My product is a bundle')" wire:model.live="is_bundle"
                :hint="__('Check this box if this product is sold in blocks of multiple units.')" />

            @if ($is_bundle)
                <x-input :label="__('Pieces per bundle')" wire:model="pieces_per_bundle" name="pieces_per_bundle" type="number"
                    min="2" max="{{ PHP_INT_MAX }}" step="1" :placeholder="__('How many pieces in each bundle?')" :required="$is_bundle" />

                <x-checkbox :label="__('Individually sellable')" wire:model="individually_sellable" :hint="__('If you can unpack your bundle and sell unit individually, check this box')" />
            @endif


            <x-slot:actions>
                <div class="w-full justify-start">
                    <x-button class="btn-primary w-full sm:w-auto" :label="__('This product is done, let\'s sell it')" type="submit" />
                </div>
            </x-slot:actions>
        </x-form>

    </x-app.app-card>
</div>
