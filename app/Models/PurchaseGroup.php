<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_number',
        'user_id',
        'date',
        'due_date',
        'notes',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getSuppliers()
    {
        return $this->purchases()->with('supplier')->get()->pluck('supplier')->unique('id');
    }
}
