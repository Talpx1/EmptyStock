@props(['profile'])

<x-menu-item title="Dashboard" icon="o-sparkles" link="{{ route('app.dashboard') }}" />

{{-- //TODO: test it shows this only for profile with shop --}}
@if ($profile->has_shop)
    <x-menu-item title="Shop" icon="o-building-storefront" link="{{ route('app.shop.edit') }}" />

    <x-menu-item title="Products" icon="o-shopping-bag" link="{{ route('app.dashboard') }}" />
@endif
