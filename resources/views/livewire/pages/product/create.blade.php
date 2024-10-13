<?php declare(strict_types=1);

use App\Models\Profile;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
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
use function Livewire\Volt\mount;
use function Livewire\Volt\computed;
use function Livewire\Volt\protect;

//TODO: test

uses([Toast::class]);

layout('layouts.app');

state([
    'shop' => fn() => type(auth()->user())->as(User::class)->active_profile->shop,
    'product' => null,
])->locked();
state(['title', 'description', 'price', 'is_bundle' => false, 'pieces_per_bundle' => null, 'individually_sellable' => false]);

$heading = computed(fn() => $this->product ? __('Give a brand new feel to :title', ['title' => $this->product->title]) : __('Create new product for :shop', ['shop' => $this->shop->name]));
$submit_btn_lbl = computed(fn() => $this->product ? __('It looks good') : __('This product is done, let\'s sell it'));
$card_description = computed(fn() => $this->product ? __('Tweak this product as you see it best fit') : __("It's time to add a new amazing product to your shop! Just fill the form down there, and publish your new best-seller."));

mount(function (?Product $product) {
    if (!$product) {
        return;
    }

    $this->title = $product->title;
    $this->description = $product->description;
    $this->price = $product->price;
    $this->is_bundle = !is_null($product->pieces_per_bundle);
    $this->pieces_per_bundle = $product->pieces_per_bundle;
    $this->individually_sellable = $product->individually_sellable;
});

$resetPiecesPerBundle = js('!$wire.is_bundle && ($wire.pieces_per_bundle=null)');

rules([
    'title' => ['required', 'string', 'min:2', 'max:255'],
    'description' => ['required', 'string', 'min:2'],
    'price' => ['numeric', 'min:0.01', 'max:' . PHP_FLOAT_MAX],
    'is_bundle' => ['nullable', 'boolean'],
    'pieces_per_bundle' => ['nullable', 'required_if_accepted:is_bundle', 'integer', 'min:2', 'max:' . PHP_INT_MAX],
    'individually_sellable' => ['nullable', 'boolean'],
]);

$onSubmit = function () {
    $validated = $this->validate();

    $this->product ? $this->updateProduct($validated) : $this->storeProduct($validated);

    $this->redirect(route('app.product.index'), navigate: true);
};

$storeProduct = protect(function (array $validated) {
    $this->shop->products()->create(Arr::except($validated, 'is_bundle'));

    $this->success(__("Woo! {$validated['title']} has been created"));
});

$updateProduct = protect(function (array $validated) {
    $this->product->update(Arr::except($validated, 'is_bundle'));

    $this->success(__("Nicely done! {$validated['title']} has been updated"));
});

?>

<div>
    <x-app.app-card>
        <x-slot:heading>{{ $this->heading }}</x-slot:heading>
        <x-slot:description>{{ $this->card_description }}</x-slot:description>

        <x-form wire:submit="onSubmit" class="mt-6 flex flex-col gap-y-6" no-separator>

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
                    <x-button class="btn-primary w-full sm:w-auto" :label="$this->submit_btn_lbl" type="submit" />
                </div>
            </x-slot:actions>
        </x-form>

    </x-app.app-card>
</div>
