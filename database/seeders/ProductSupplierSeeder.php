<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Associate products with suppliers
        $products = Product::all();
        $suppliers = Supplier::all();

        // Example: Associate each product with at least one supplier
        foreach ($products as $product) {
            // Randomly select 1-2 suppliers for each product
            $selectedSuppliers = $suppliers->random(rand(1, 2));

            foreach ($selectedSuppliers as $supplier) {
                // Generate a random purchase price between 50000 and 500000
                $purchasePrice = rand(50000, 500000);

                // Associate product with supplier
                $product->suppliers()->attach($supplier->id, [
                    'purchase_price' => $purchasePrice
                ]);
            }
        }
    }
}
