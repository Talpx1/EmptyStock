<?php

declare(strict_types=1);
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\QueryException;

use function Pest\Laravel\assertDatabaseHas;

describe('database constraints', function () {
    test('title is required', function () {
        Product::factory()->create(['title' => null]);
    })->throws(QueryException::class, 'title', 23000);

    test('description is required', function () {
        Product::factory()->create(['description' => null]);
    })->throws(QueryException::class, 'description', 23000);

    test('price is required', function () {
        Product::factory()->create(['price' => null]);
    })->throws(QueryException::class, 'price', 23000);

    test('price has precision of 8', function () {
        Product::factory()->create(['price' => 123456789]);
    })->throws(QueryException::class, "Out of range value for column 'price'", 22003);

    test('price has 2 decimal values', function () {
        Product::factory()->create(['price' => 0.0001]);
        assertDatabaseHas(Product::class, ['price' => 0.00]);

        Product::factory()->create(['price' => 0.109]);
        assertDatabaseHas(Product::class, ['price' => 0.11]);
    });

    test('pieces_per_bundle is nullable', function () {
        Product::factory()->create(['pieces_per_bundle' => null]);
        assertDatabaseHas(Product::class, ['pieces_per_bundle' => null]);
    });

    test('individually_sellable defaults to false', function () {
        $product = Product::factory()->withoutIndividuallySellable()->make();

        expect(array_keys($product->getAttributes()))->not->toContain('individually_sellable');

        $product->save();

        assertDatabaseHas(Product::class, ['individually_sellable' => false]);
    });

    test('shop_id is required', function () {
        Product::factory()->create(['shop_id' => null]);
    })->throws(QueryException::class, 'shop_id', 23000);

    test('if related Shop id gets updated Product shop_id also get updated', function () {
        $shop = Shop::factory()->create();
        $product = Product::factory()->for($shop)->create();

        expect($product->shop->id)->toEqual($shop->id);
        expect($product->shop_id)->toEqual($shop->id);

        $shop->id = 999;
        $shop->save();

        expect($shop->fresh()->id)->toEqual(999);

        expect($product->fresh()->shop_id)->toEqual($shop->fresh()->id);
        expect($product->fresh()->shop->fresh()->id)->toEqual($shop->fresh()->id);
    });

    test('Product restrict deletion of related Shop', function () {
        $shop = Shop::factory()->create();
        $product = Product::factory()->for($shop)->create();

        expect($product->shop->id)->toEqual($shop->id);
        expect($product->shop_id)->toEqual($shop->id);

        $shop->delete();
    })->throws(QueryException::class, 'FOREIGN KEY', 23000);
});

describe('accessors and mutators', function () {
    it('belongs to shop', function () {
        $shop = Shop::factory()->create();
        $product = Product::factory()->for($shop)->create();

        expect($product->shop)->toBeInstanceOf(Shop::class);
        expect($product->shop)->toBe($shop);
    });
});

describe('relations', function () {});
