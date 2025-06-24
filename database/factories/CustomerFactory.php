<?php

namespace Database\Factories;

// use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curtomer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // protected $model = Customer::class;
    protected $model = \App\Models\Customer::class;

    public function definition()
    {
        return [
            'nik' => $this->faker->unique()->numerify('32########'),
            'nama' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'desa_id' => $this->faker->numberBetween(1, 100),
            'kecamatan_id' => $this->faker->numberBetween(1, 50),
            'kabupaten_id' => $this->faker->numberBetween(1, 20),
            'provinsi_id' => $this->faker->numberBetween(1, 10),
            'desa_nama' => $this->faker->citySuffix(),
            'kecamatan_nama' => $this->faker->city(),
            'kabupaten_nama' => $this->faker->state(),
            'provinsi_nama' => $this->faker->state(),
        ];
    }
}
