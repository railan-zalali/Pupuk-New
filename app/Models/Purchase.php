<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'user_id',
        'date',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function receipts()
    {
        return $this->hasMany(PurchaseReceipt::class);
    }

    // Helper methods to check status
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPartiallyReceived()
    {
        return $this->status === 'partially_received';
    }

    public function isReceived()
    {
        return $this->status === 'received';
    }

    // Get total received quantity
    public function getTotalReceivedQuantity()
    {
        return $this->purchaseDetails->sum('received_quantity');
    }

    // Get total ordered quantity
    public function getTotalOrderedQuantity()
    {
        return $this->purchaseDetails->sum('quantity');
    }

    // Get receipt progress percentage
    public function getReceiptProgressPercentage()
    {
        $totalOrdered = $this->getTotalOrderedQuantity();
        if ($totalOrdered == 0) return 0;

        $totalReceived = $this->getTotalReceivedQuantity();
        return round(($totalReceived / $totalOrdered) * 100);
    }
}
