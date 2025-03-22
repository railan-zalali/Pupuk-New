<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(3)->create();
        Customer::factory(10)->create();
        Product::factory(20)->create();
        Supplier::factory(3)->create();

        // Memanggil RolePermissionSeeder
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
