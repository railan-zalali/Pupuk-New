<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific suppliers for seeds and medicines
        $suppliers = [
            // Suppliers for seeds (benih)
            [
                'name' => 'PT Benih Unggul Indonesia',
                'phone' => '021-12345678',
                'address' => 'Jl. Raya Pertanian No. 123, Jakarta Selatan',
                'description' => 'Supplier benih berkualitas tinggi untuk berbagai jenis tanaman'
            ],
            [
                'name' => 'CV Syngenta Agro Indonesia',
                'phone' => '031-222333',
                'address' => 'Jl. Basuki Rahmat No. 555, Surabaya',
                'description' => 'Distributor pestisida dan teknologi pertanian modern'
            ],
            [
                'name' => 'Toko Agro Medika',
                'phone' => '0274-111222',
                'address' => 'Jl. Solo No. 666, Yogyakarta',
                'description' => 'Supplier obat-obatan pertanian dan vitamin tanaman'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Also create some additional suppliers using factory for variety
        Supplier::factory()->count(10)->create();
    }
}
