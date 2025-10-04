<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Pagination parameters
        $lowStockPage = $request->get('low_stock_page', 1);
        $transactionsPage = $request->get('transactions_page', 1);
        $expiredPage = $request->get('expired_page', 1);
        $expiringPage = $request->get('expiring_page', 1);
        $perPage = 5; // Items per page for each section

        // Data untuk cards
        $totalSalesToday = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        $totalSalesYesterday = Sale::whereDate('created_at', Carbon::yesterday())->sum('total_amount');
        $totalSalesThisMonth = Sale::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $totalSalesLastMonth = Sale::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total_amount');

        $data['totalProducts'] = Product::count();
        $data['totalSalesToday'] = $totalSalesToday;
        $data['totalSalesThisMonth'] = $totalSalesThisMonth;
        // Calculate low stock products using subquery for actual stock from batches
        $data['lowStockProducts'] = Product::whereHas('batches', function($query) {
                $query->where('remaining_quantity', '>', 0);
            })
            ->whereRaw('(SELECT COALESCE(SUM(remaining_quantity), 0) FROM product_batches WHERE product_id = products.id) <= min_stock')
            ->count();

        // Menghitung persentase perubahan harian
        $salesChangeToday = 0;
        if ($totalSalesYesterday > 0) {
            $salesChangeToday = (($totalSalesToday - $totalSalesYesterday) / $totalSalesYesterday) * 100;
        }

        // Menghitung persentase perubahan bulanan
        $salesChangeThisMonth = 0;
        if ($totalSalesLastMonth > 0) {
            $salesChangeThisMonth = (($totalSalesThisMonth - $totalSalesLastMonth) / $totalSalesLastMonth) * 100;
        }

        $data['salesChangeToday'] = $salesChangeToday;
        $data['salesChangeThisMonth'] = $salesChangeThisMonth;

        // Data hutang jatuh tempo dalam 1 bulan
        $data['upcomingCredits'] = Sale::where('payment_method', 'credit')
            ->where('payment_status', '!=', 'paid')
            ->where('due_date', '<=', Carbon::now()->addMonth())
            ->with('customer')
            ->latest('due_date')
            ->limit(5)
            ->get();

        $data['totalUpcomingCredits'] = Sale::where('payment_method', 'credit')
            ->where('payment_status', '!=', 'paid')
            ->where('due_date', '<=', Carbon::now()->addMonth())
            ->count();

        $data['totalCreditAmount'] = Sale::where('payment_method', 'credit')
            ->where('payment_status', '!=', 'paid')
            ->where('due_date', '<=', Carbon::now()->addMonth())
            ->sum('remaining_amount');

        // Data untuk tabel - Get low stock products with pagination
        $lowStockQuery = Product::with(['batches', 'category'])
            ->whereHas('batches', function($query) {
                $query->where('remaining_quantity', '>', 0);
            })
            ->whereRaw('(SELECT COALESCE(SUM(remaining_quantity), 0) FROM product_batches WHERE product_id = products.id) <= min_stock')
            ->latest();
        
        $data['lowStockAlerts'] = $lowStockQuery->paginate($perPage, ['*'], 'low_stock_page', $lowStockPage);
        $data['lowStockAlerts']->appends($request->except('low_stock_page'));

        $recentTransactionsQuery = Sale::with('user')->latest();
        $data['recentTransactions'] = $recentTransactionsQuery->paginate($perPage, ['*'], 'transactions_page', $transactionsPage);
        $data['recentTransactions']->appends($request->except('transactions_page'));

        // Data untuk grafik - Menggunakan pendekatan yang lebih sederhana
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Sale::whereDate('created_at', $date)->sum('total_amount');

            $dates->push([
                'date' => $date->format('Y-m-d'),
                'total' => $amount
            ]);
        }
        $data['dailySales'] = $dates;

        $data['salesTrend'] = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::today()->subMonths($i);
            return [
                'date' => $date->format('Y-m'),
                'total' => Sale::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_amount')
            ];
        });


        // Data untuk produk terlaris
        $productsWithSales = Product::with(['saleDetails.sale' => function ($query) {
            $query->whereNull('deleted_at');
        }])
            ->get()
            ->map(function ($product) {
                $totalSold = $product->saleDetails->sum('quantity');
                return [
                    'name' => $product->name,
                    'total_sold' => $totalSold
                ];
            })
            ->sortByDesc('total_sold')
            ->take(5)
            ->values();

        $data['topProducts'] = $productsWithSales;

        // Data untuk stok keluar harian
        $data['dailyOutgoingStock'] = StockMovement::where('type', 'out')
            ->whereDate('created_at', Carbon::today())
            ->sum('quantity');

        // Data untuk stok masuk harian
        $data['dailyIncomingStock'] = StockMovement::where('type', 'in')
            ->whereDate('created_at', Carbon::today())
            ->sum('quantity');

        // Get products that will expire in the next 30 days with pagination
        $expiringProductsQuery = Product::whereHas('productBatches', function ($query) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', now())
                    ->where('expiry_date', '<=', now()->addDays(30))
                    ->where('remaining_quantity', '>', 0);
            })
            ->with(['productBatches' => function ($query) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '>=', now())
                    ->where('expiry_date', '<=', now()->addDays(30))
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('expiry_date');
            }]);
        
        $data['expiringProducts'] = $expiringProductsQuery->paginate($perPage, ['*'], 'expiring_page', $expiringPage);
        $data['expiringProducts']->appends($request->except('expiring_page'));

        // Get products that are already expired with pagination
        $expiredProductsQuery = Product::whereHas('productBatches', function ($query) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', now())
                    ->where('remaining_quantity', '>', 0);
            })
            ->with(['productBatches' => function ($query) {
                $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<', now())
                    ->where('remaining_quantity', '>', 0)
                    ->orderBy('expiry_date');
            }]);
        
        $data['expiredProducts'] = $expiredProductsQuery->paginate($perPage, ['*'], 'expired_page', $expiredPage);
        $data['expiredProducts']->appends($request->except('expired_page'));

        // Pastikan produk yang akan kadaluarsa ditampilkan terlepas dari status stok

        // Data untuk draft transaksi
        $data['draftSales'] = Sale::whereIn('status', ['draft', 'processing'])
            ->with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $data['totalDrafts'] = Sale::whereIn('status', ['draft', 'processing'])->count();

        // Draft yang akan expire dalam 3 hari
        $data['expiringDrafts'] = Sale::where('status', 'draft')
            ->where('created_at', '<=', Carbon::now()->subDays(27))
            ->count();
        $expiringDrafts = Cache::remember('expiring_drafts', 3600, function () {
            return Sale::drafts()
                ->where('created_at', '<=', now()->subDays(27))
                ->with('customer')
                ->get();
        });

        return view('dashboard', [
            'data' => $data,
            'expiringDrafts' => $expiringDrafts,
        ]);
    }
    public function dailyStockDetails()
    {
        try {
            $outgoingStockDetails = StockMovement::where('type', 'out')
                ->whereDate('created_at', Carbon::today())
                ->with(['product'])
                ->get();

            // Load reference only for non-initial movements with proper error handling
            $outgoingStockDetails->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    try {
                        $movement->load('reference');
                        // Load nested relationships based on reference type
                        if ($movement->reference) {
                            if (in_array($movement->reference_type, ['sale', 'App\Models\Sale']) && method_exists($movement->reference, 'customer')) {
                                $movement->reference->load('customer');
                            }
                            if (in_array($movement->reference_type, ['purchase', 'App\Models\Purchase']) && method_exists($movement->reference, 'user')) {
                                $movement->reference->load('user');
                            }
                        }
                    } catch (\Exception $e) {
                        // Log error but continue processing
                        \Log::warning('Failed to load reference for stock movement: ' . $e->getMessage());
                    }
                }
            });

            $incomingStockDetails = StockMovement::where('type', 'in')
                ->whereDate('created_at', Carbon::today())
                ->with(['product'])
                ->get();

            // Load reference only for non-initial movements with proper error handling
            $incomingStockDetails->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    try {
                        $movement->load('reference');
                        // Load nested relationships based on reference type
                        if ($movement->reference) {
                            if (in_array($movement->reference_type, ['purchase', 'App\Models\Purchase']) && method_exists($movement->reference, 'user')) {
                                $movement->reference->load('user');
                            }
                            if (in_array($movement->reference_type, ['purchase_receipt', 'App\Models\PurchaseReceipt']) && method_exists($movement->reference, 'user')) {
                                $movement->reference->load('user');
                            }
                        }
                    } catch (\Exception $e) {
                        // Log error but continue processing
                        \Log::warning('Failed to load reference for stock movement: ' . $e->getMessage());
                    }
                }
            });

            return view('stock-details', [
                'outgoingStockDetails' => $outgoingStockDetails,
                'incomingStockDetails' => $incomingStockDetails,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in dailyStockDetails: ' . $e->getMessage());
            return view('stock-details', [
                'outgoingStockDetails' => collect(),
                'incomingStockDetails' => collect(),
                'error' => 'Terjadi kesalahan saat memuat data stok harian.'
            ]);
        }
    }
    public function weeklyStockDetails()
    {
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $outgoingStock = StockMovement::where('type', 'out')
                ->whereDate('created_at', $date)
                ->with(['product'])
                ->get();
                
            // Load reference only for non-initial and non-adjustment movements
            $outgoingStock->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    $movement->load('reference');
                }
            });
            
            $incomingStock = StockMovement::where('type', 'in')
                ->whereDate('created_at', $date)
                ->with(['product'])
                ->get();
                
            // Load reference only for non-initial and non-adjustment movements
            $incomingStock->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    $movement->load('reference');
                }
            });

            $dates->push([
                'date' => $date,
                'outgoing_stock' => $outgoingStock,
                'incoming_stock' => $incomingStock,
            ]);
        }

        return view('weekly-stock-details', [
            'stockDetails' => $dates,
        ]);
    }

    public function monthlyStockDetails()
    {
        $months = collect();
        
        // Get data for 3 months: 2 previous months + current month
        for ($i = 2; $i >= 0; $i--) {
            $startOfMonth = Carbon::today()->subMonths($i)->startOfMonth();
            $endOfMonth = Carbon::today()->subMonths($i)->endOfMonth();
            
            $outgoingStock = StockMovement::where('type', 'out')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with(['product'])
                ->get();
                
            // Load reference only for non-initial and non-adjustment movements
            $outgoingStock->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    $movement->load('reference');
                }
            });
                
            $incomingStock = StockMovement::where('type', 'in')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->with(['product'])
                ->get();
                
            // Load reference only for non-initial and non-adjustment movements
            $incomingStock->each(function ($movement) {
                if ($movement->reference_type && $movement->reference_type !== 'initial' && $movement->reference_type !== 'adjustment') {
                    $movement->load('reference');
                }
            });

            $months->push([
                'month' => $startOfMonth,
                'outgoing_stock' => $outgoingStock,
                'incoming_stock' => $incomingStock,
                'outgoing_total' => $outgoingStock->sum('quantity'),
                'incoming_total' => $incomingStock->sum('quantity'),
            ]);
        }

        return view('monthly-stock-details', [
            'stockDetails' => $months,
        ]);
    }
}
