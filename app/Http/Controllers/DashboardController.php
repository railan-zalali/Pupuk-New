<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk cards
        $totalSalesToday = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        $totalSalesYesterday = Sale::whereDate('created_at', Carbon::yesterday())->sum('total_amount');
        $totalSalesThisMonth = Sale::whereMonth('created_at', Carbon::now()->month)->sum('total_amount');
        $totalSalesLastMonth = Sale::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total_amount');

        $data['totalProducts'] = Product::count();
        $data['totalSalesToday'] = $totalSalesToday;
        $data['totalSalesThisMonth'] = $totalSalesThisMonth;
        $data['lowStockProducts'] = Product::whereColumn('stock', '<=', 'min_stock')->count();

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

        // Data untuk tabel
        $data['lowStockAlerts'] = Product::whereColumn('stock', '<=', 'min_stock')
            ->latest()
            ->limit(5)
            ->get();

        $data['recentTransactions'] = Sale::with('user')
            ->latest()
            ->limit(5)
            ->get();

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

        // Get products that will expire in the next 30 days
        $data['expiringProducts'] = Product::whereHas('productUnits', function ($query) {
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

        // Data untuk draft transaksi
        $data['draftSales'] = Sale::where('status', 'draft')
            ->with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $data['totalDrafts'] = Sale::where('status', 'draft')->count();

        // Draft yang akan expire dalam 3 hari
        $data['expiringDrafts'] = Sale::where('status', 'draft')
            ->where('created_at', '<=', Carbon::now()->subDays(27))
            ->count();

        return view('dashboard', $data);
    }
    public function dailyStockDetails()
    {
        $outgoingStockDetails = StockMovement::where('type', 'out')
            ->whereDate('created_at', Carbon::today())
            ->with('product')
            ->get();

        $incomingStockDetails = StockMovement::where('type', 'in')
            ->whereDate('created_at', Carbon::today())
            ->with('product')
            ->get();

        return view('stock-details', [
            'outgoingStockDetails' => $outgoingStockDetails,
            'incomingStockDetails' => $incomingStockDetails,
        ]);
    }
    public function weeklyStockDetails()
    {
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

        return view('weekly-stock-details', [
            'stockDetails' => $dates,
        ]);
    }
}
