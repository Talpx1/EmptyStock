<?php

declare(strict_types=1);
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

use function Pest\Laravel\assertDatabaseHas;

describe('database constraints', function () {
    test('name is required', function () {
        Shop::factory()->create(['name' => null]);
    })->throws(QueryException::class, 'name', 23000);

    test('name must be unique', function () {
        Shop::factory()->create(['name' => 'Test']);
        Shop::factory()->create(['name' => 'Test']);
    })->throws(QueryException::class, 'name', 23000);

    test('slogan is required', function () {
        Shop::factory()->create(['slogan' => null]);
        assertDatabaseHas(Shop::class, ['slogan' => null]);
    });

    test('description is nullable', function () {
        Shop::factory()->create(['description' => null]);
        assertDatabaseHas(Shop::class, ['description' => null]);
    });

    test('vat_number is required', function () {
        Shop::factory()->create(['vat_number' => null]);
    })->throws(QueryException::class, 'vat_number', 23000);

    test('vat_number must be unique', function () {
        Shop::factory()->create(['vat_number' => '123']);
        Shop::factory()->create(['vat_number' => '123']);
    })->throws(QueryException::class, 'vat_number', 23000);

    test('iban is required', function () {
        Shop::factory()->create(['iban' => null]);
    })->throws(QueryException::class, 'iban', 23000);

    test('iban must be unique', function () {
        Shop::factory()->create(['iban' => '123']);
        Shop::factory()->create(['iban' => '123']);
    })->throws(QueryException::class, 'iban', 23000);
});

describe('accessors and mutators', function () {});

describe('relations', function () {
    test('has many products', function () {
        $shop = Shop::factory()->create();

        $shop_products = Product::factory()->count(3)->for($shop)->create();
        $other_shop_products = Product::factory()->count(4)->for(Shop::factory())->create();

        expect($shop->products)->toHaveCount(3);
        expect($shop->products)->toBeInstanceOf(Collection::class);
        expect($shop->products)->toContainOnlyInstancesOf(Product::class);
        expect($shop->products)->toContain($shop_products);
        expect($shop->products)->not->toContain($other_shop_products);
    });
});
