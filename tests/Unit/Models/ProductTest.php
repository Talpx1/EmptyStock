<?php

declare(strict_types=1);
use App\Models\Company;
use App\Models\Product;
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

    test('company_id is required', function () {
        Product::factory()->create(['company_id' => null]);
    })->throws(QueryException::class, 'company_id', 23000);

    test('if related Company id gets updated Product company_id also get updated', function () {
        $company = Company::factory()->create();
        $product = Product::factory()->for($company)->create();

        expect($product->company->id)->toEqual($company->id);
        expect($product->company_id)->toEqual($company->id);

        $company->id = 999;
        $company->save();

        expect($company->fresh()->id)->toEqual(999);

        expect($product->fresh()->company_id)->toEqual($company->fresh()->id);
        expect($product->fresh()->company->fresh()->id)->toEqual($company->fresh()->id);
    });

    test('Product restrict deletion of related Company', function () {
        $company = Company::factory()->create();
        $product = Product::factory()->for($company)->create();

        expect($product->company->id)->toEqual($company->id);
        expect($product->company_id)->toEqual($company->id);

        $company->delete();
    })->throws(QueryException::class, 'FOREIGN KEY', 23000);
});

describe('accessors and mutators', function () {
    it('belongs to company', function () {
        $company = Company::factory()->create();
        $product = Product::factory()->for($company)->create();

        expect($product->company)->toBeInstanceOf(Company::class);
        expect($product->company)->toBe($company);
    });
});

describe('relations', function () {});
