<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    
    public function index()
    {
        // Get all dashboard data using service
        $todaySales = $this->dashboardService->getTodaySalesData();
        $monthSales = $this->dashboardService->getThisMonthSalesData();
        $basicStats = $this->dashboardService->getBasicStats();
        $upcomingCredits = $this->dashboardService->getUpcomingCredits();
        $lowStockAlerts = $this->dashboardService->getLowStockAlerts();
        $recentTransactions = $this->dashboardService->getRecentTransactions();
        $dailySales = $this->dashboardService->getDailySalesChart();
        $salesTrend = $this->dashboardService->getMonthlySalesTrend();
        $topProducts = $this->dashboardService->getTopProducts();
        $stockMovements = $this->dashboardService->getDailyStockMovements();
        $expiringProducts = $this->dashboardService->getExpiringProducts();
        $draftSales = $this->dashboardService->getDraftSalesData();
        $expiringDrafts = $this->dashboardService->getExpiringDraftsDetails();
        
        // Prepare data array for view
        $data = [
            'totalProducts' => $basicStats['total_products'],
            'totalSalesToday' => $todaySales['today'],
            'totalSalesThisMonth' => $monthSales['this_month'],
            'lowStockProducts' => $basicStats['low_stock_count'],
            'salesChangeToday' => $todaySales['percentage_change'],
            'salesChangeThisMonth' => $monthSales['percentage_change'],
            'upcomingCredits' => $upcomingCredits['credits'],
            'totalUpcomingCredits' => $upcomingCredits['total_count'],
            'totalCreditAmount' => $upcomingCredits['total_amount'],
            'lowStockAlerts' => $lowStockAlerts,
            'recentTransactions' => $recentTransactions,
            'dailySales' => $dailySales,
            'salesTrend' => $salesTrend,
            'topProducts' => $topProducts,
            'dailyOutgoingStock' => $stockMovements['outgoing'],
            'dailyIncomingStock' => $stockMovements['incoming'],
            'expiringProducts' => $expiringProducts,
            'draftSales' => $draftSales['drafts'],
            'totalDrafts' => $draftSales['total_drafts'],
            'expiringDrafts' => $draftSales['expiring_drafts']
        ];
        
        return view('dashboard', [
            'data' => $data,
            'expiringDrafts' => $expiringDrafts,
        ]);
    }
    
    public function dailyStockDetails()
    {
        $stockDetails = $this->dashboardService->getDailyStockDetails();
        
        return view('stock-details', [
            'outgoingStockDetails' => $stockDetails['outgoing'],
            'incomingStockDetails' => $stockDetails['incoming'],
        ]);
    }
    
    public function weeklyStockDetails()
    {
        $stockDetails = $this->dashboardService->getWeeklyStockDetails();
        
        return view('weekly-stock-details', [
            'stockDetails' => $stockDetails,
        ]);
    }
}
