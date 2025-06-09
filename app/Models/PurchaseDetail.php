<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'unit_id',
        'quantity',
        'base_quantity',
        'purchase_price',
        'subtotal',
        'conversion_factor',
    ];

    protected $casts = [
        'purchase_price' => 'integer',
        'subtotal' => 'integer',
        'quantity' => 'integer',
        'base_quantity' => 'integer',
        'conversion_factor' => 'integer',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    public function receiptDetails()
    {
        return $this->hasMany(PurchaseReceiptDetail::class);
    }

    // Check if fully received
    public function isFullyReceived()
    {
        return $this->received_quantity >= $this->quantity;
    }

    // Get remaining quantity to receive
    public function getRemainingQuantity()
    {
        return max(0, $this->quantity - $this->received_quantity);
    }
}
