<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get today's sales data with percentage change
     */
    public function getTodaySalesData(): array
    {
        return Cache::remember('dashboard.today_sales', 300, function () {
            $today = Sale::today()->completed()->sum('total_amount');
            $yesterday = Sale::yesterday()->completed()->sum('total_amount');
            
            $percentageChange = $yesterday > 0 
                ? (($today - $yesterday) / $yesterday) * 100 
                : 0;
            
            return [
                'today' => $today,
                'yesterday' => $yesterday,
                'percentage_change' => round($percentageChange, 2)
            ];
        });
    }
    
    /**
     * Get this month's sales data with percentage change
     */
    public function getThisMonthSalesData(): array
    {
        return Cache::remember('dashboard.month_sales', 600, function () {
            $thisMonth = Sale::thisMonth()->completed()->sum('total_amount');
            $lastMonth = Sale::lastMonth()->completed()->sum('total_amount');
            
            $percentageChange = $lastMonth > 0 
                ? (($thisMonth - $lastMonth) / $lastMonth) * 100 
                : 0;
            
            return [
                'this_month' => $thisMonth,
                'last_month' => $lastMonth,
                'percentage_change' => round($percentageChange, 2)
            ];
        });
    }
    
    /**
     * Get basic dashboard statistics
     */
    public function getBasicStats(): array
    {
        return Cache::remember('dashboard.basic_stats', 600, function () {
            return [
                'total_products' => Product::count(),
                'low_stock_count' => Product::whereColumn('stock', '<=', 'min_stock')->count()
            ];
        });
    }
    
    /**
     * Get upcoming credit payments
     */
    public function getUpcomingCredits(): array
    {
        return Cache::remember('dashboard.upcoming_credits', 300, function () {
            // Base query untuk upcoming credits
            $baseQuery = Sale::where('payment_method', 'credit')
                ->where('payment_status', '!=', 'paid')
                ->where('due_date', '<=', Carbon::now()->addMonth());
            
            // Get credits dengan eager loading spesifik kolom
            $upcomingCredits = (clone $baseQuery)
                ->select(['id', 'customer_id', 'total_amount', 'remaining_amount', 'due_date', 'created_at'])
                ->with('customer:id,nama')
                ->latest('due_date')
                ->limit(5)
                ->get();
            
            // Get aggregated data dalam satu query
            $aggregateData = (clone $baseQuery)
                ->selectRaw('COUNT(*) as total_count, SUM(remaining_amount) as total_amount')
                ->first();
            
            return [
                'credits' => $upcomingCredits,
                'total_count' => $aggregateData->total_count ?? 0,
                'total_amount' => $aggregateData->total_amount ?? 0
            ];
        });
    }
    
    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts(): Collection
    {
        return Cache::remember('dashboard.low_stock_alerts', 600, function () {
            return Product::whereColumn('stock', '<=', 'min_stock')
                ->select(['id', 'category_id', 'name', 'stock', 'min_stock', 'created_at'])
                ->with('category:id,name')
                ->latest()
                ->limit(5)
                ->get();
        });
    }
    
    /**
     * Get recent transactions
     */
    public function getRecentTransactions(): Collection
    {
        return Cache::remember('dashboard.recent_transactions', 300, function () {
            return Sale::with(['customer:id,nama', 'user:id,name'])
                      ->select('id', 'invoice_number', 'customer_id', 'user_id', 'total_amount', 'payment_method', 'status', 'created_at')
                      ->latest()
                      ->limit(5)
                      ->get();
        });
    }
    
    /**
     * Get daily sales data for chart (last 7 days)
     */
    public function getDailySalesChart(): Collection
    {
        return Cache::remember('dashboard.daily_sales_chart', 300, function () {
            $startDate = Carbon::today()->subDays(6);
            $endDate = Carbon::today();
            
            // Get all sales data in one query
            $salesData = Sale::whereBetween('created_at', [$startDate, $endDate->copy()->endOfDay()])
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->groupByRaw('DATE(created_at)')
                ->orderByRaw('DATE(created_at)')
                ->pluck('total', 'date');
            
            // Fill missing dates with 0
            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dateStr = $date->format('Y-m-d');
                
                $dates->push([
                    'date' => $dateStr,
                    'total' => $salesData->get($dateStr, 0)
                ]);
            }
            
            return $dates;
        });
    }
    
    /**
     * Get monthly sales trend (last 12 months)
     */
    public function getMonthlySalesTrend(): Collection
    {
        return Cache::remember('dashboard.monthly_sales_trend', 1800, function () {
            $startDate = Carbon::today()->subMonths(11)->startOfMonth();
            $endDate = Carbon::today()->endOfMonth();
            
            // Get all monthly sales data in one query
             $salesData = Sale::whereBetween('created_at', [$startDate, $endDate])
                 ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
                 ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                 ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m")')
                 ->pluck('total', 'month');
            
            // Fill missing months with 0
            return collect(range(0, 11))->map(function ($i) use ($salesData) {
                $date = Carbon::today()->subMonths($i);
                $monthStr = $date->format('Y-m');
                
                return [
                    'date' => $monthStr,
                    'total' => $salesData->get($monthStr, 0)
                ];
            })->reverse()->values();
        });
    }
    
    /**
     * Get top selling products
     */
    public function getTopProducts(): Collection
    {
        return Cache::remember('dashboard.top_products', 600, function () {
            return Product::select(['products.id', 'products.name'])
                ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
                ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
                ->whereNull('sales.deleted_at')
                ->selectRaw('products.name, SUM(sale_details.quantity) as total_sold')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'total_sold' => (int) $item->total_sold
                    ];
                });
        });
    }
    
    /**
     * Get daily stock movements
     */
    public function getDailyStockMovements(): array
    {
        return Cache::remember('dashboard.daily_stock_movements', 300, function () {
            $movements = StockMovement::whereDate('created_at', Carbon::today())
                ->selectRaw('type, SUM(quantity) as total_quantity')
                ->groupBy('type')
                ->pluck('total_quantity', 'type');
            
            return [
                'outgoing' => $movements->get('out', 0),
                'incoming' => $movements->get('in', 0)
            ];
        });
    }
    
    /**
     * Get products expiring in next 30 days
     */
    public function getExpiringProducts(): Collection
    {
        return Cache::remember('dashboard.expiring_products', 1800, function () {
            return Product::whereHas('productUnits', function ($query) {
                $query->whereNotNull('expire_date')
                    ->where('expire_date', '>=', now())
                    ->where('expire_date', '<=', now()->addDays(30));
            })
            ->with(['productUnits' => function ($query) {
                $query->whereNotNull('expire_date')
                    ->where('expire_date', '>=', now())
                    ->where('expire_date', '<=', now()->addDays(30))
                    ->orderBy('expire_date');
            }])
            ->get();
        });
    }
    
    /**
     * Get draft sales data
     */
    public function getDraftSalesData(): array
    {
        return Cache::remember('dashboard.draft_sales', 300, function () {
            // Get draft sales dengan eager loading spesifik kolom
            $drafts = Sale::drafts()
                ->select(['id', 'customer_id', 'invoice_number', 'total_amount', 'created_at'])
                ->with('customer:id,nama')
                ->latest()
                ->limit(5)
                ->get();
            
            // Get aggregated data dalam satu query
            $aggregateData = Sale::drafts()
                ->selectRaw('COUNT(*) as total_drafts, SUM(CASE WHEN created_at <= ? THEN 1 ELSE 0 END) as expiring_drafts')
                ->addBinding(Carbon::now()->subDays(27))
                ->first();
            
            return [
                'drafts' => $drafts,
                'total_drafts' => $aggregateData->total_drafts ?? 0,
                'expiring_drafts' => $aggregateData->expiring_drafts ?? 0
            ];
        });
    }
    
    /**
     * Get expiring drafts with details
     */
    public function getExpiringDraftsDetails(): Collection
    {
        return Cache::remember('dashboard.expiring_drafts_details', 3600, function () {
            return Sale::drafts()
                ->where('created_at', '<=', now()->subDays(27))
                ->select(['id', 'customer_id', 'invoice_number', 'total_amount', 'created_at'])
                ->with('customer:id,nama')
                ->get();
        });
    }
    
    /**
     * Get daily stock details for specific date
     */
    public function getDailyStockDetails(?Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        
        $outgoingStockDetails = StockMovement::where('type', 'out')
            ->whereDate('created_at', $date)
            ->select(['id', 'product_id', 'quantity', 'type', 'description', 'created_at'])
            ->with('product:id,name')
            ->get();
        
        $incomingStockDetails = StockMovement::where('type', 'in')
            ->whereDate('created_at', $date)
            ->select(['id', 'product_id', 'quantity', 'type', 'description', 'created_at'])
            ->with('product:id,name')
            ->get();
        
        return [
            'outgoing' => $outgoingStockDetails,
            'incoming' => $incomingStockDetails
        ];
    }
    
    /**
     * Get weekly stock details (last 7 days)
     */
    public function getWeeklyStockDetails(): Collection
    {
        return Cache::remember('dashboard.weekly_stock_details', 600, function () {
            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $outgoingStock = StockMovement::where('type', 'out')
                    ->whereDate('created_at', $date)
                    ->with('product')
                    ->get();
                $incomingStock = StockMovement::where('type', 'in')
                    ->whereDate('created_at', $date)
                    ->with('product')
                    ->get();
                
                $dates->push([
                    'date' => $date,
                    'outgoing_stock' => $outgoingStock,
                    'incoming_stock' => $incomingStock,
                ]);
            }
            return $dates;
        });
    }
    
    /**
     * Get overdue debts
     */
    public function getOverdueDebts(): Collection
    {
        return Cache::remember('dashboard.overdue_debts', 600, function () {
            return Sale::overdue()
                      ->select(['id', 'customer_id', 'invoice_number', 'total_amount', 'remaining_amount', 'due_date', 'created_at'])
                      ->with('customer:id,nama')
                      ->orderBy('due_date', 'asc')
                      ->limit(5)
                      ->get();
        });
    }
    
    /**
     * Clear all dashboard cache
     */
    public function clearCache(): void
    {
        $cacheKeys = [
            'dashboard.today_sales',
            'dashboard.month_sales',
            'dashboard.basic_stats',
            'dashboard.upcoming_credits',
            'dashboard.low_stock_alerts',
            'dashboard.recent_transactions',
            'dashboard.daily_sales_chart',
            'dashboard.monthly_sales_trend',
            'dashboard.top_products',
            'dashboard.daily_stock_movements',
            'dashboard.expiring_products',
            'dashboard.draft_sales',
            'dashboard.expiring_drafts_details',
            'dashboard.weekly_stock_details',
            'dashboard.overdue_debts'
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}