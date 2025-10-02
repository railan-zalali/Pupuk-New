<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductBatchFactory extends Factory
{
    protected $model = ProductBatch::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(10, 200);
        $remainingQuantity = $this->faker->numberBetween(0, $quantity);
        
        return [
            'product_id' => Product::factory(),
            'batch_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'quantity' => $quantity,
            'remaining_quantity' => $remainingQuantity,
            'production_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+2 years'),
            'purchase_price' => $this->faker->randomFloat(2, 1000, 50000),
            'purchase_id' => null,
        ];
    }
}