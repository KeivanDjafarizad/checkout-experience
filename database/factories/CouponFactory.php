<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->lexify('??????'),
            'active' => true,
            'min_price' => $this->faker->randomFloat(2, 0, 10),
            'max_price' => $this->faker->randomFloat(2, 10, 100),
            'amount' => $this->faker->randomFloat(2, 0, 10),
            'associated_product_ids' => null,
        ];
    }
}
