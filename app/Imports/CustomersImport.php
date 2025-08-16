<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{

    /**
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Validasi data
                if (empty($row['nik']) || empty($row['nama'])) {
                    Log::warning('Baris dilewati: NIK atau Nama kosong', ['data' => $row]);
                    continue;
                }

                // Normalisasi data - hapus tanda hubung dari NIK
                $cleanNik = str_replace(['-', ' ', '.'], '', $row['nik']);
                
                $customerData = [
                    'nik' => $cleanNik,
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'] ?? '',
                    'desa_id' => '0',
                    'kecamatan_id' => '0',
                    'kabupaten_id' => '0',
                    'provinsi_id' => '0',
                    'desa_nama' => $row['desa'] ?? '',
                    'kecamatan_nama' => $row['kecamatan'] ?? '',
                    'kabupaten_nama' => $row['kabupaten'] ?? '',
                    'provinsi_nama' => $row['provinsi'] ?? 'JAWA BARAT',
                ];

                // Check if customer with this NIK already exists
                $existingCustomer = Customer::where('nik', $row['nik'])->first();

                if ($existingCustomer) {
                    // Update existing customer
                    $existingCustomer->update($customerData);
                    Log::info('Customer diperbarui', ['nik' => $row['nik']]);
                } else {
                    // Create new customer
                    Customer::create($customerData);
                    Log::info('Customer baru dibuat', ['nik' => $row['nik']]);
                }
            } catch (\Exception $e) {
                Log::error('Error saat import customer', [
                    'error' => $e->getMessage(),
                    'data' => $row
                ]);
            }
        }
    }


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', function ($attribute, $value, $fail) {
                // Hapus tanda hubung, spasi, dan titik untuk validasi panjang
                $cleanValue = str_replace(['-', ' ', '.'], '', $value);
                if (strlen($cleanValue) !== 16) {
                    $fail('NIK harus 16 digit (tanpa tanda hubung, spasi, atau titik)');
                }
            }],
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nik.required' => 'Kolom NIK wajib diisi',
            'nama.required' => 'Kolom Nama wajib diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'desa.max' => 'Nama desa maksimal 100 karakter',
            'kecamatan.max' => 'Nama kecamatan maksimal 100 karakter',
            'kabupaten.max' => 'Nama kabupaten maksimal 100 karakter',
            'provinsi.max' => 'Nama provinsi maksimal 100 karakter',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
