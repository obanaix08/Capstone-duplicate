<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->bothify('MAT-???-###')),
            'name' => fake()->words(2, true),
            'unit' => fake()->randomElement(['pcs', 'sheet', 'kg', 'L']),
            'stock' => fake()->randomFloat(2, 0, 500),
            'low_stock_threshold' => fake()->randomFloat(2, 5, 50),
        ];
    }
}
