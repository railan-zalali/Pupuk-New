<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'user_id',
        'receipt_number',
        'receipt_date',
        'receipt_file',
        'notes'
    ];

    protected $casts = [
        'receipt_date' => 'datetime'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiptDetails()
    {
        return $this->hasMany(PurchaseReceiptDetail::class);
    }
}
