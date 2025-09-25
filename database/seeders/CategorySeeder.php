<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific categories for seeds and medicines
        $categories = [
            // Seed categories (benih)
            [
                'name' => 'Benih',
                'description' => 'Kategori untuk semua jenis benih tanaman'
            ],
            // [
            //     'name' => 'Benih Sayuran',
            //     'description' => 'Benih untuk tanaman sayuran seperti cabai, tomat, kangkung, bayam'
            // ],
            // [
            //     'name' => 'Benih Buah',
            //     'description' => 'Benih untuk tanaman buah-buahan seperti melon, semangka, pepaya'
            // ],
            // [
            //     'name' => 'Benih Padi',
            //     'description' => 'Benih padi varietas unggul dan hibrida'
            // ],
            // [
            //     'name' => 'Benih Jagung',
            //     'description' => 'Benih jagung hibrida dan varietas lokal'
            // ],
            
            // Medicine categories (obat-obatan)
            [
                'name' => 'Obat-obatan',
                'description' => 'Kategori untuk semua jenis obat-obatan pertanian'
            ],
            [
                'name' => 'Pupuk Organik',
                'description' => 'Pupuk dari bahan-bahan organik alami'
            ],
            [
                'name' => 'Pupuk Kimia',
                'description' => 'Pupuk anorganik seperti NPK, Urea, TSP'
            ],
            // [
            //     'name' => 'Pestisida',
            //     'description' => 'Obat pembasmi hama dan penyakit tanaman'
            // ],
            // [
            //     'name' => 'Fungisida',
            //     'description' => 'Obat anti jamur untuk tanaman'
            // ],
            // [
            //     'name' => 'Insektisida',
            //     'description' => 'Obat pembasmi serangga hama'
            // ],
            // [
            //     'name' => 'Herbisida',
            //     'description' => 'Obat pembasmi gulma dan rumput liar'
            // ],
            // [
            //     'name' => 'Vitamin Tanaman',
            //     'description' => 'Suplemen dan vitamin untuk pertumbuhan tanaman'
            // ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Also create some additional categories using factory for variety
        Category::factory(3)->create();
    }
}
