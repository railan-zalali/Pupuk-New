<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'is_base_unit'
    ];
    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class, 'unit_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_units', 'unit_id', 'product_id')
            ->withPivot('conversion_factor', 'purchase_price', 'selling_price', 'is_default');
    }
}
