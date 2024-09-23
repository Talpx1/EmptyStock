<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->unique()->company(),
            'slogan' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'vat_number' => fake()->unique()->numerify(strtoupper(Str::random(2)).'###########'),
            'iban' => fake()->iban(),
        ];
    }
}
