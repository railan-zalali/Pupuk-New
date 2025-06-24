<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_receipt_id',
        'purchase_detail_id',
        'product_id',
        'quantity',
        'base_quantity',
        'receipt_quantity',
        'received_quantity',
        'expire_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'base_quantity' => 'integer',
    ];

    public function receipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id');
    }

    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
