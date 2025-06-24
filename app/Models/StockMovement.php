<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'reference_id',
        'reference_type',
        'quantity',
        'movement_type', // 'in' or 'out'
        'type',
        'before_stock',
        'after_stock',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo();
    }
}
