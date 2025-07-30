<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function items()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    
    /**
     * Scope a query to only include draft sales.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }
}
