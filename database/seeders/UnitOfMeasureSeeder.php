<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common units for agricultural products
        $units = [
            [
                'name' => 'Pieces',
                'abbreviation' => 'Pcs',
                'is_base_unit' => true,
            ],
            [
                'name' => 'Kilogram',
                'abbreviation' => 'Kg',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'L',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Milliliter',
                'abbreviation' => 'mL',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'Box',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Sack',
                'abbreviation' => 'Sack',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Ton',
                'abbreviation' => 'Ton',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Bottle',
                'abbreviation' => 'Btl',
                'is_base_unit' => false,
            ],
            [
                'name' => 'Pack',
                'abbreviation' => 'Pack',
                'is_base_unit' => false,
            ],
        ];

        foreach ($units as $unit) {
            UnitOfMeasure::updateOrCreate(
                ['name' => $unit['name']],
                $unit
            );
        }
    }
}
