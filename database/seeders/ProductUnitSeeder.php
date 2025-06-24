<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create base units
        UnitOfMeasure::create([
            'name' => 'Pieces',
            'abbreviation' => 'Pcs',
            'is_base_unit' => true
        ]);

        UnitOfMeasure::create([
            'name' => 'Kilogram',
            'abbreviation' => 'Kg',
            'is_base_unit' => false
        ]);

        UnitOfMeasure::create([
            'name' => 'Gram',
            'abbreviation' => 'g',
            'is_base_unit' => false
        ]);

        UnitOfMeasure::create([
            'name' => 'Liter',
            'abbreviation' => 'L',
            'is_base_unit' => false
        ]);

        UnitOfMeasure::create([
            'name' => 'Box',
            'abbreviation' => 'Box',
            'is_base_unit' => false
        ]);

        UnitOfMeasure::create([
            'name' => 'Sack',
            'abbreviation' => 'Sack',
            'is_base_unit' => false
        ]);
    }
}
