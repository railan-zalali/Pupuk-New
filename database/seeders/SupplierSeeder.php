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
                'name' => 'CV Sumber Benih Nusantara',
                'phone' => '0274-987654',
                'address' => 'Jl. Malioboro No. 456, Yogyakarta',
                'description' => 'Distributor benih sayuran dan buah-buahan'
            ],
            [
                'name' => 'Toko Benih Makmur',
                'phone' => '0361-555123',
                'address' => 'Jl. Gajah Mada No. 789, Denpasar, Bali',
                'description' => 'Penyedia benih lokal dan impor'
            ],
            [
                'name' => 'PT Agro Seed Technology',
                'phone' => '031-777888',
                'address' => 'Jl. Industri No. 321, Surabaya',
                'description' => 'Supplier benih hibrida dan teknologi pertanian'
            ],
            
            // Suppliers for medicines (obat-obatan)
            [
                'name' => 'PT Kimia Farma Agro',
                'phone' => '021-333444',
                'address' => 'Jl. Veteran No. 111, Jakarta Pusat',
                'description' => 'Distributor pestisida dan obat-obatan pertanian'
            ],
            [
                'name' => 'CV Agro Chemical Solutions',
                'phone' => '022-666777',
                'address' => 'Jl. Dago No. 222, Bandung',
                'description' => 'Supplier fungisida, insektisida, dan herbisida'
            ],
            [
                'name' => 'Toko Obat Tani Sejahtera',
                'phone' => '0251-888999',
                'address' => 'Jl. Pajajaran No. 333, Bogor',
                'description' => 'Penyedia obat-obatan dan pupuk organik'
            ],
            [
                'name' => 'PT Bayer CropScience Indonesia',
                'phone' => '021-555666',
                'address' => 'Jl. TB Simatupang No. 444, Jakarta Selatan',
                'description' => 'Supplier produk perlindungan tanaman berkualitas internasional'
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
