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
        'min_stock',
        'stock_method',
        'requires_expiry_date',
        'is_perishable',
        'expiry_warning_days',
        'strict_expiry_validation'
    ];

    protected $casts = [
        'requires_expiry_date' => 'boolean',
        'is_perishable' => 'boolean',
        'strict_expiry_validation' => 'boolean',
        'expiry_warning_days' => 'integer',
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
    
    /**
     * Get the batches for this product.
     */
    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }
    
    /**
     * Get available batches with remaining stock (FIFO order).
     */
    public function availableBatches()
    {
        return $this->batches()->hasStock()->fifo();
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
    
    // Relasi langsung ke ProductUnit untuk menghindari error relationship
    public function productUnitsWithUnit()
    {
        return $this->hasMany(ProductUnit::class)->with('unit');
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

        return implode(' + ', $result) ?: '0';
    }

    /**
     * Get the effective stock method for this product
     */
    public function getEffectiveStockMethod()
    {
        if ($this->stock_method === 'auto') {
            // Auto-determine based on product characteristics
            if ($this->is_perishable || $this->requires_expiry_date) {
                return 'fefo';
            }
            return 'fifo';
        }
        
        return $this->stock_method;
    }

    /**
     * Calculate actual stock from batches (replaces direct stock field)
     * This is the source of truth for stock levels
     */
    public function getActualStockAttribute()
    {
        return $this->batches()->sum('remaining_quantity');
    }

    /**
     * Get available stock (alias for actual_stock for backward compatibility)
     */
    public function getAvailableStockAttribute()
    {
        return $this->getActualStockAttribute();
    }

    /**
     * Check if product has sufficient stock
     */
    public function hasSufficientStock($requiredQuantity)
    {
        return $this->actual_stock >= $requiredQuantity;
    }

    /**
     * Get stock status (low, normal, out)
     */
    public function getStockStatusAttribute()
    {
        $actualStock = $this->actual_stock;
        
        if ($actualStock <= 0) {
            return 'out';
        } elseif ($actualStock <= $this->min_stock) {
            return 'low';
        }
        
        return 'normal';
    }

    /**
     * Sync the legacy stock field with actual batch stock
     * This method should be called after batch operations to maintain consistency
     * Eventually, the stock field should be removed from the database
     */
    public function syncStockFromBatches()
    {
        $actualStock = $this->actual_stock;
        if ($this->stock !== $actualStock) {
            $this->update(['stock' => $actualStock]);
        }
        return $actualStock;
    }

    /**
     * Check if this product uses FEFO method
     */
    public function usesFefo()
    {
        return $this->getEffectiveStockMethod() === 'fefo';
    }

    /**
     * Check if this product uses FIFO method
     */
    public function usesFifo()
    {
        return $this->getEffectiveStockMethod() === 'fifo';
    }

    /**
     * Get expiry warning days (product-specific or system default)
     */
    public function getExpiryWarningDays()
    {
        return $this->expiry_warning_days ?? 7; // Default to 7 days
    }

    /**
     * Check if product requires expiry date validation
     */
    public function requiresExpiryDate()
    {
        return $this->requires_expiry_date || $this->is_perishable;
    }

    /**
     * Get stock method display name
     */
    public function getStockMethodDisplayAttribute()
    {
        $methods = [
            'auto' => 'Otomatis',
            'fifo' => 'FIFO (First In, First Out)',
            'fefo' => 'FEFO (First Expired, First Out)'
        ];

        return $methods[$this->stock_method] ?? 'Otomatis';
    }

    /**
     * Get effective stock method display name
     */
    public function getEffectiveStockMethodDisplayAttribute()
    {
        $methods = [
            'fifo' => 'FIFO (First In, First Out)',
            'fefo' => 'FEFO (First Expired, First Out)'
        ];

        return $methods[$this->getEffectiveStockMethod()] ?? 'FIFO';
    }

    /**
     * Scope for products that require expiry dates
     */
    public function scopeRequiresExpiry($query)
    {
        return $query->where(function ($q) {
            $q->where('requires_expiry_date', true)
              ->orWhere('is_perishable', true);
        });
    }

    /**
     * Scope for perishable products
     */
    public function scopePerishable($query)
    {
        return $query->where('is_perishable', true);
    }

    /**
     * Scope for products using FEFO method
     */
    public function scopeUsesFefo($query)
    {
        return $query->where(function ($q) {
            $q->where('stock_method', 'fefo')
              ->orWhere(function ($subQ) {
                  $subQ->where('stock_method', 'auto')
                       ->where(function ($autoQ) {
                           $autoQ->where('is_perishable', true)
                                  ->orWhere('requires_expiry_date', true);
                       });
              });
        });
    }
}
