<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_number',
        'purchase_group_id',
        'supplier_id',
        'user_id',
        'date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseGroup()
    {
        return $this->belongsTo(PurchaseGroup::class);
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

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
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
