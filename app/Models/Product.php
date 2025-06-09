<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'code',
        'description',
        'image_path',
        'purchase_price',
        'selling_price',
        'stock',
        'min_stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)
            ->withPivot('purchase_price')
            ->withTimestamps();
    }

    public function units()
    {
        return $this->belongsToMany(UnitOfMeasure::class, 'product_units', 'product_id', 'unit_id')
            ->withPivot('id', 'purchase_price', 'selling_price', 'conversion_factor', 'is_default', 'expire_date')
            ->withTimestamps();
    }

    public function defaultUnit()
    {
        return $this->hasOne(ProductUnit::class)->where('is_default', true);
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    // Get formatted stock display with multiple units
    public function getFormattedStockDisplay()
    {
        $baseStock = $this->stock;
        $result = [];

        // Get all units ordered by conversion factor (largest first)
        $units = $this->units()
            ->orderByDesc('conversion_factor')
            ->get();

        $remainingStock = $baseStock;

        foreach ($units as $productUnit) {
            if ($productUnit->conversion_factor <= $remainingStock) {
                $unitCount = floor($remainingStock / $productUnit->conversion_factor);
                $remainingStock -= $unitCount * $productUnit->conversion_factor;

                if ($unitCount > 0) {
                    $result[] = $unitCount . ' ' . $productUnit->abbreviation;
                }
            }
        }

        return implode(' + ', $result);
    }
}
