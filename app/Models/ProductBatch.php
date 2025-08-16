<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',
        'remaining_quantity',
        'production_date',
        'expiry_date',
        'purchase_price',
        'purchase_id'
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the product that owns the batch.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the purchase that created this batch.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the stock movements for this batch.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'batch_id');
    }

    /**
     * Scope a query to only include batches with remaining stock.
     */
    public function scopeHasStock($query)
    {
        return $query->where('remaining_quantity', '>', 0);
    }

    /**
     * Scope a query to order by FIFO (oldest batches first).
     */
    public function scopeFifo($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to order by FEFO (earliest expiry first).
     */
    public function scopeFefo($query)
    {
        return $query->orderBy('expiry_date', 'asc');
    }
}
