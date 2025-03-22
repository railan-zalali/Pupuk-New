<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'date',
        'total_amount',
        'discount',
        'paid_amount',
        'down_payment',
        'change_amount',
        'payment_method',
        'vehicle_type',
        'vehicle_number',
        'payment_status',
        'status',
        'remaining_amount',
        'due_date',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
