<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'desa_id',
        'kecamatan_id',
        'kabupaten_id',
        'provinsi_id',
        'desa_nama',
        'kecamatan_nama',
        'kabupaten_nama',
        'provinsi_nama'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
