<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'purchase_price',
        'selling_price',
        'expire_date',
        'is_default'
    ];

    protected $casts = [
        'conversion_factor' => 'integer',
        'purchase_price' => 'integer',
        'selling_price' => 'integer',
        'is_default' => 'boolean',
        'expire_date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    // Convert quantity from this unit to base unit
    public function toBaseUnit($quantity)
    {
        return $quantity * $this->conversion_factor;
    }

    // Convert quantity from base unit to this unit
    public function fromBaseUnit($baseQuantity)
    {
        if ($this->conversion_factor == 0) return 0;
        return $baseQuantity / $this->conversion_factor;
    }

    // Check if there's enough stock for a given quantity in this unit
    public function hasEnoughStock($quantity)
    {
        $baseQuantity = $this->toBaseUnit($quantity);
        return $this->product->stock >= $baseQuantity;
    }

    // Get available stock in this unit
    public function getAvailableStock()
    {
        return $this->fromBaseUnit($this->product->stock);
    }
}
