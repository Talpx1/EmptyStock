<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->name(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, .01, 999.99),
            'pieces_per_bundle' => null,
            'individually_sellable' => false,
        ];
    }

    public function asBundle(?int $pieces_per_bundle = null): static {
        return $this->state(fn (array $attributes) => [
            'pieces_per_bundle' => $pieces_per_bundle ?? rand(2, 30),
        ]);
    }

    public function asIndividuallySellableBundle(?int $pieces_per_bundle = null): static {
        return $this->state(fn (array $attributes) => [
            'pieces_per_bundle' => $pieces_per_bundle ?? rand(2, 30),
            'individually_sellable' => true,
        ]);
    }

    public function withoutIndividuallySellable(): static {
        return $this->state(fn (array $attributes) => $attributes)
            ->afterMaking(function (Product $model) {
                $model->setRawAttributes(Arr::except($model->getAttributes(), ['individually_sellable']));
            });
    }
}
