<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
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

        return view('dashboard', $data);
    }
}
