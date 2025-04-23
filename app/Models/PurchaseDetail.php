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
        'quantity',
        'received_quantity',
        'purchase_price',
        'subtotal'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
