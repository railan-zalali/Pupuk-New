<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->unique()->bothify('PRD###'),
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory(),
            'purchase_price' => $this->faker->numberBetween(5000, 50000),
            'selling_price' => $this->faker->numberBetween(6000, 60000),
            'stock' => $this->faker->numberBetween(10, 200),
            'min_stock' => $this->faker->numberBetween(1, 5),
        ];
    }
}
