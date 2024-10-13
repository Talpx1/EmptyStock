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
use function Livewire\Volt\usesPagination;
use function Livewire\Volt\with;
use function Livewire\Volt\state;

//TODO: test
layout('layouts.app');

usesPagination();

state([
    'shop' => fn() => type(auth()->user())->as(User::class)->active_profile->shop,
])->locked();

state(['per_page' => 10]);

with([
    'products' => fn() => $this->shop->products()->paginate($this->per_page),
    'headers' => [['key' => 'title', 'label' => __('Title')], ['key' => 'price', 'label' => __('Price')]],
]);

?>

<div>
    <x-button class="btn-primary mb-6 sm:w-auto" :label="__('Let\'s create a new one')" link="{{ route('app.product.create') }}" />
    <x-app.app-card>
        <x-slot:heading>{{ __(':shop\'s products', ['shop' => $shop->name]) }}</x-slot:heading>
        <x-table :headers="$headers" :rows="$products" with-pagination per-page="per_page" :per-page-values="[10, 30, 50, 100]">
            <x-slot:empty>
                <x-icon name="o-cube" :label="_('The stock is already empty')" />
            </x-slot:empty>
        </x-table>

    </x-app.app-card>
</div>
