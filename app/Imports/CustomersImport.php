<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'nik'           => $row[0], // Kolom pertama di Excel
            'nama'          => $row[1], // Kolom kedua di Excel
            'alamat'        => $row[2], // Kolom ketiga di Excel
            'desa_id'       => $row[3],
            'kecamatan_id'  => $row[4],
            'kabupaten_id' => $row[5],
            'provinsi_id'   => $row[6],
            'desa_nama'     => $row[7],
            'kecamatan_nama' => $row[8],
            'kabupaten_nama' => $row[9],
            'provinsi_nama'  => $row[10],
        ]);
    }
}
