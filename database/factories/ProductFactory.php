<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->lexify("??????????"),
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 200),
            'stock_qty' => $this->faker->randomNumber(),
            'active' => true,
        ];
    }
}
