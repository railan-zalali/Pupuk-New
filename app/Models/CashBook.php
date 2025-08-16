<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'reference_number',
        'debit',
        'credit',
        'balance'
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'integer',
        'credit' => 'integer',
        'balance' => 'integer'
    ];
}