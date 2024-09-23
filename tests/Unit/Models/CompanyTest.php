<?php

declare(strict_types=1);
use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;

use function Pest\Laravel\assertDatabaseHas;

describe('database constraints', function () {
    test('name is required', function () {
        Company::factory()->create(['name' => null]);
    })->throws(QueryException::class, 'name', 23000);

    test('name must be unique', function () {
        Company::factory()->create(['name' => 'Test']);
        Company::factory()->create(['name' => 'Test']);
    })->throws(QueryException::class, 'name', 23000);

    test('slogan is required', function () {
        Company::factory()->create(['slogan' => null]);
        assertDatabaseHas(Company::class, ['slogan' => null]);
    });

    test('description is nullable', function () {
        Company::factory()->create(['description' => null]);
        assertDatabaseHas(Company::class, ['description' => null]);
    });

    test('vat_number is required', function () {
        Company::factory()->create(['vat_number' => null]);
    })->throws(QueryException::class, 'vat_number', 23000);

    test('vat_number must be unique', function () {
        Company::factory()->create(['vat_number' => '123']);
        Company::factory()->create(['vat_number' => '123']);
    })->throws(QueryException::class, 'vat_number', 23000);

    test('iban is required', function () {
        Company::factory()->create(['iban' => null]);
    })->throws(QueryException::class, 'iban', 23000);

    test('iban must be unique', function () {
        Company::factory()->create(['iban' => '123']);
        Company::factory()->create(['iban' => '123']);
    })->throws(QueryException::class, 'iban', 23000);
});

describe('accessors and mutators', function () {});

describe('relations', function () {
    test('has many products', function () {
        $company = Company::factory()->create();

        $company_products = Product::factory()->count(3)->for($company)->create();
        $other_company_products = Product::factory()->count(4)->for(Company::factory())->create();

        expect($company->products)->toHaveCount(3);
        expect($company->products)->toBeInstanceOf(Collection::class);
        expect($company->products)->toContainOnlyInstancesOf(Product::class);
        expect($company->products)->toContain($company_products);
        expect($company->products)->not->toContain($other_company_products);
    });
});
