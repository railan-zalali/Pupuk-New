<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    use SkipsErrors;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if customer with this NIK already exists
        $existingCustomer = Customer::where('nik', $row['nik'])->first();

        if ($existingCustomer) {
            // Update existing customer
            $existingCustomer->update([
                'nama' => $row['nama'],
                'alamat' => $row['alamat'],
                'desa_nama' => $row['desa'],
                'kecamatan_nama' => $row['kecamatan'],
                'kabupaten_nama' => $row['kabupaten'],
                'provinsi_nama' => $row['provinsi'] ?? 'Indonesia',
            ]);

            return null; // Don't create a new model
        }

        // Create new customer
        return new Customer([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'alamat' => $row['alamat'],
            'desa_id' => '0',
            'kecamatan_id' => '0',
            'kabupaten_id' => '0',
            'provinsi_id' => '0',
            'desa_nama' => $row['desa'],
            'kecamatan_nama' => $row['kecamatan'],
            'kabupaten_nama' => $row['kabupaten'],
            'provinsi_nama' => $row['provinsi'] ?? 'Indonesia',
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nik' => 'required|string',
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'desa' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'desa.required' => 'Desa wajib diisi',
            'kecamatan.required' => 'Kecamatan wajib diisi',
            'kabupaten.required' => 'Kabupaten wajib diisi',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
}
