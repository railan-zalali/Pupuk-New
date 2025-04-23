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
        Category::factory(1)->create();
        Customer::factory(1)->create();
        Product::factory(1)->create();
        Supplier::factory(1)->create();

        // Memanggil RolePermissionSeeder
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
