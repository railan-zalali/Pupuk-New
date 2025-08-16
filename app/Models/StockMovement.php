<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_id',
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
    
    /**
     * Get the batch associated with this stock movement.
     */
    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo();
    }
}
