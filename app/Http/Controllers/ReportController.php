<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Traits\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use ReportExport;

    public function index()
    {
        return view('reports.index');
    }

    public function purchases(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        $purchases = Purchase::with(['supplier'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(15);

        $data = [
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'total_purchases' => $purchases->count(),
                'total_amount' => $purchases->sum('total_amount'),
                'average_purchase' => $purchases->avg('total_amount'),
                'status_counts' => $purchases->groupBy('status')->map->count()
            ],
            'headers' => [
                'Date' => 'date',
                'Invoice' => 'invoice_number',
                'Supplier' => 'supplier_name',
                'Amount' => 'total_amount',
                'Status' => 'status'
            ],
            'items' => $purchases
        ];

        // Handle export atau tampilkan view
        if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
            return $this->handleExport(
                $data,
                'purchases',
                'purchase_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
            );
        }

        return view('reports.purchases', $data);
    }

    public function sales(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

            // Validate date range
            if ($startDate > $endDate) {
                return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            }

            // Optimize query with eager loading and specific columns
            $sales = Sale::select([
                'id',
                'invoice_number',
                'created_at',
                'total_amount',
                'payment_method',
                'customer_id',
                'user_id',
                'deleted_at'
            ])
                ->with([
                    'customer:id,nama',
                    'user:id,name',
                    'saleDetails:id,sale_id,product_id,quantity,price,subtotal',
                    'saleDetails.product:id,name'
                ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->when($request->payment_method, function ($query, $method) {
                    $query->where('payment_method', $method);
                })
                ->latest()
                ->get();

            // Prepare chart data for sales by date
            $salesChartData = $sales->groupBy(function ($sale) {
                return $sale->created_at->format('d/m/Y');
            })->map(function ($group) {
                return $group->sum('total_amount');
            });

            // Calculate total products sold using collection methods
            $totalProductsSold = $sales->sum(function ($sale) {
                return $sale->saleDetails->sum('quantity');
            });

            $avgProductsPerTransaction = $sales->count() > 0
                ? $totalProductsSold / $sales->count()
                : 0;

            $data = [
                'sales' => $sales,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'salesChartData' => $salesChartData,
                'summary' => [
                    'total_sales' => $sales->count(),
                    'total_amount' => $sales->sum('total_amount'),
                    'average_sale' => $sales->count() > 0 ? $sales->sum('total_amount') / $sales->count() : 0,
                    'payment_methods' => $sales->groupBy('payment_method')
                        ->map(fn($group) => $group->count()),
                    'total_products_sold' => $totalProductsSold,
                    'avg_products_per_transaction' => round($avgProductsPerTransaction, 1)
                ],
                'headers' => [
                    'Tanggal' => 'date',
                    'Faktur' => 'invoice_number',
                    'Pelanggan' => 'customer_name',
                    'Total' => 'total_amount',
                    'Pembayaran' => 'payment_method',
                    'Status' => 'status'
                ],
                'items' => $sales
            ];


            // Handle export atau tampilkan view
            if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
                return $this->handleExport(
                    $data,
                    'sales',
                    'sales_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
                );
            }

            return view('reports.sales', $data);
        } catch (\Exception $e) {
            Log::error('Sales Report Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memuat laporan: ' . $e->getMessage());
        }
    }

    public function cashFlow(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Get all transactions within date range
        $transactions = CashBook::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('id')
            ->paginate(20);

        // Calculate opening balance (balance before start date)
        $openingBalance = CashBook::where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first()?->balance ?? 0;

        // Get all transactions for calculations (without pagination)
        $allTransactions = CashBook::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Calculate totals
        $totalDebit = $allTransactions->sum('debit');
        $totalCredit = $allTransactions->sum('credit');
        $closingBalance = $openingBalance + $totalDebit - $totalCredit;

        // Prepare chart data
        $dailyData = $allTransactions->groupBy(function ($transaction) {
            return $transaction->date->format('d/m/Y');
        });

        $chartLabels = [];
        $debitData = [];
        $creditData = [];
        $balanceData = [];
        $currentBalance = $openingBalance;

        foreach ($dailyData as $date => $dailyTransactions) {
            $chartLabels[] = $date;
            $dailyDebit = $dailyTransactions->sum('debit');
            $dailyCredit = $dailyTransactions->sum('credit');
            $currentBalance += $dailyDebit - $dailyCredit;

            $debitData[] = $dailyDebit;
            $creditData[] = $dailyCredit;
            $balanceData[] = $currentBalance;
        }

        $data = [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'opening_balance' => $openingBalance,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'closing_balance' => $closingBalance
            ],
            'chart_data' => [
                'labels' => $chartLabels,
                'debit' => $debitData,
                'credit' => $creditData,
                'balance' => $balanceData
            ],
            'headers' => [
                'Tanggal' => 'date',
                'Deskripsi' => 'description',
                'Debit' => 'debit',
                'Kredit' => 'credit',
                'Saldo' => 'balance'
            ],
            'items' => $transactions,
            'date' => now()
        ];

        return $this->handleExport(
            $data,
            'cash-flow',
            'cash_flow_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
        );
    }

    public function accounts(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $purchases = Purchase::whereBetween('date', [$startDate, $endDate])
            ->with('supplier')
            ->paginate(15);

        $sales = Sale::whereBetween('date', [$startDate, $endDate])
            ->with('customer')
            ->paginate(15);

        $payables = $purchases->where('payment_status', '!=', 'paid');
        $receivables = $sales->where('payment_status', '!=', 'paid');

        // Prepare accounts data based on type filter
        $type = $request->get('type', 'receivable');

        if ($type === 'payable') {
            // Menggunakan paginate untuk $accounts
            $accounts = Purchase::whereBetween('date', [$startDate, $endDate])
                ->where('payment_status', '!=', 'paid')
                ->with('supplier')
                ->paginate(15);
            $totalAmount = $payables->sum('remaining_amount');
            $entities = $payables->pluck('supplier.name')->unique()->count();
        } else {
            // Menggunakan paginate untuk $accounts
            $accounts = Sale::whereBetween('date', [$startDate, $endDate])
                ->where('payment_status', '!=', 'paid')
                ->with('customer')
                ->paginate(15);
            $totalAmount = $receivables->sum('remaining_amount');
            $entities = $receivables->pluck('customer.nama')->unique()->count();
        }

        // Prepare chart data
        $chartData = [
            'labels' => $accounts->getCollection()->pluck('date')->map->format('d/m/Y')->toArray(),
            'total' => $accounts->getCollection()->pluck('total_amount')->toArray(),
            'paid' => $accounts->getCollection()->map(fn($acc) => $acc->total_amount - $acc->remaining_amount)->toArray(),
            'remaining' => $accounts->getCollection()->pluck('remaining_amount')->toArray()
        ];

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'accounts' => $accounts,
            'payables' => $payables,
            'receivables' => $receivables,
            'summary' => [
                'total_amount' => $totalAmount,
                'total_transactions' => $accounts->total(),
                'average_amount' => $accounts->total() > 0 ? $totalAmount / $accounts->total() : 0,
                'total_entities' => $entities
            ],
            'chart_data' => $chartData,
            'headers' => [
                'Tanggal' => 'date',
                'Faktur' => 'invoice_number',
                'Supplier/Pelanggan' => 'customer_name',
                'Total' => 'total_amount',
                'Sisa' => 'remaining_amount',
                'Status' => 'payment_status'
            ],
            'items' => $type === 'payable' ? $accounts : $accounts,
            'date' => now(),
            'purchases' => $purchases,
            'sales' => $sales
        ];

        // Handle export atau tampilkan view
        if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
            return $this->handleExport(
                $data,
                'accounts',
                'accounts_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
            );
        }

        return view('reports.accounts', $data);
    }

    public function stock()
    {
        // $products = Product::with('category')
        //     ->withSum('purchaseDetails as total_purchased', 'quantity')
        //     ->withSum('saleDetails as total_sold', 'quantity')
        //     ->get()
        //     ->map(function ($product) {
        //         $product->stock_value = $product->stock * $product->purchase_price;
        //         return $product;
        //     });
        $products = Product::with('category')
            ->withSum('purchaseDetails as total_purchased', 'quantity')
            ->withSum('saleDetails as total_sold', 'quantity')
            ->paginate(20); // Add pagination here

        $productsCollection = $products->getCollection()->map(function ($product) {
            $product->stock_value = $product->stock * $product->purchase_price;
            return $product;
        });

        // Replace the collection in the paginator with our modified collection
        $products->setCollection($productsCollection);

        $data = [
            'products' => $products,
            'summary' => [
                'total_products' => $products->count(),
                'total_stock_value' => $products->sum('stock_value'),
                'low_stock_count' => $products->where('stock', '<=', 'min_stock')->count(),
            ],
            'headers' => [
                'Code' => 'code',
                'Name' => 'name',
                'Category' => 'category_name',
                'Stock' => 'stock',
                'Min Stock' => 'min_stock',
                'Value' => 'stock_value'
            ],
            'items' => $products,
            'date' => now()
        ];

        return $this->handleExport(
            $data,
            'stock',
            'stock_report_' . now()->format('Y-m-d')
        );
    }

    public function fifoStock(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Ambil semua produk dengan batch yang tersedia
        $products = Product::with(['category', 'supplier', 'availableBatches'])->paginate(20);

        // Hitung nilai persediaan berdasarkan FIFO untuk setiap produk
        $productsCollection = $products->getCollection()->map(function ($product) {
            $fifoValue = $product->availableBatches->sum(function ($batch) {
                return $batch->remaining_quantity * $batch->purchase_price;
            });

            $product->fifo_value = $fifoValue;
            $product->batch_count = $product->availableBatches->count();
            $product->oldest_batch = $product->availableBatches->sortBy('created_at')->first()?->batch_number ?? '-';
            $product->newest_batch = $product->availableBatches->sortByDesc('created_at')->first()?->batch_number ?? '-';

            return $product;
        });

        // Replace the collection in the paginator with our modified collection
        $products->setCollection($productsCollection);

        // Hitung total nilai persediaan FIFO
        $totalFifoValue = $productsCollection->sum('fifo_value');
        $totalActiveBatches = ProductBatch::whereHas('product')->where('remaining_quantity', '>', 0)->count();

        // Prepare data for view and export
        $data = [
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'total_products' => Product::count(),
                'total_fifo_value' => $totalFifoValue,
                'total_active_batches' => $totalActiveBatches
            ],
            'headers' => [
                'Code' => 'code',
                'Name' => 'name',
                'Category' => 'category_name',
                'Stock' => 'stock',
                'FIFO Value' => 'fifo_value',
                'Batch Count' => 'batch_count',
                'Oldest Batch' => 'oldest_batch',
                'Newest Batch' => 'newest_batch'
            ],
            'items' => $products,
            'date' => now()
        ];

        return $this->handleExport(
            $data,
            'fifo-stock',
            'fifo_stock_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
        );
    }

    /**
     * Laporan Stok Masuk (harian, mingguan, bulanan)
     */
    public function stockIn(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        
        // Validasi range tanggal
        if ($startDate > $endDate) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Query untuk stok masuk (dari pembelian)
        $stockInQuery = PurchaseDetail::select(
                'products.id as product_id',
                'products.name as product_name',
                'products.code as product_code',
                'categories.name as category_name',
                DB::raw('SUM(purchase_details.quantity) as total_quantity'),
                DB::raw('SUM(purchase_details.quantity * purchase_details.price) as total_value')
            )
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->whereBetween('purchases.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name', 'products.code', 'categories.name');

        // Berdasarkan periode yang dipilih
        switch ($period) {
            case 'weekly':
                $stockInData = $this->getWeeklyStockData($stockInQuery, $startDate, $endDate, 'purchases.created_at');
                $chartTitle = 'Stok Masuk Mingguan';
                break;
            case 'monthly':
                $stockInData = $this->getMonthlyStockData($stockInQuery, $startDate, $endDate, 'purchases.created_at');
                $chartTitle = 'Stok Masuk Bulanan';
                break;
            default: // daily
                $stockInData = $this->getDailyStockData($stockInQuery, $startDate, $endDate, 'purchases.created_at');
                $chartTitle = 'Stok Masuk Harian';
                break;
        }

        // Ambil data produk untuk tabel
        $products = $stockInQuery->paginate(20);

        // Hitung total
        $totalQuantity = $products->sum('total_quantity');
        $totalValue = $products->sum('total_value');

        $data = [
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'chartTitle' => $chartTitle,
            'chartData' => $stockInData,
            'summary' => [
                'total_products' => $products->count(),
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
                'average_value' => $products->count() > 0 ? $totalValue / $products->count() : 0
            ],
            'headers' => [
                'Kode' => 'product_code',
                'Produk' => 'product_name',
                'Kategori' => 'category_name',
                'Jumlah' => 'total_quantity',
                'Nilai' => 'total_value'
            ],
            'items' => $products,
            'date' => now()
        ];

        // Handle export atau tampilkan view
        if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
            return $this->handleExport(
                $data,
                'stock-in',
                'stock_in_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
            );
        }

        return view('reports.stock-in', $data);
    }

    /**
     * Laporan Stok Keluar (harian, mingguan, bulanan)
     */
    public function stockOut(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        
        // Validasi range tanggal
        if ($startDate > $endDate) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Query untuk stok keluar (dari penjualan)
        $stockOutQuery = SaleDetail::select(
                'products.id as product_id',
                'products.name as product_name',
                'products.code as product_code',
                'categories.name as category_name',
                DB::raw('SUM(sale_details.quantity) as total_quantity'),
                DB::raw('SUM(sale_details.quantity * sale_details.price) as total_value')
            )
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name', 'products.code', 'categories.name');

        // Berdasarkan periode yang dipilih
        switch ($period) {
            case 'weekly':
                $stockOutData = $this->getWeeklyStockData($stockOutQuery, $startDate, $endDate, 'sales.created_at');
                $chartTitle = 'Stok Keluar Mingguan';
                break;
            case 'monthly':
                $stockOutData = $this->getMonthlyStockData($stockOutQuery, $startDate, $endDate, 'sales.created_at');
                $chartTitle = 'Stok Keluar Bulanan';
                break;
            default: // daily
                $stockOutData = $this->getDailyStockData($stockOutQuery, $startDate, $endDate, 'sales.created_at');
                $chartTitle = 'Stok Keluar Harian';
                break;
        }

        // Ambil data produk untuk tabel
        $products = $stockOutQuery->paginate(20);

        // Hitung total
        $totalQuantity = $products->sum('total_quantity');
        $totalValue = $products->sum('total_value');

        $data = [
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'chartTitle' => $chartTitle,
            'chartData' => $stockOutData,
            'summary' => [
                'total_products' => $products->count(),
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
                'average_value' => $products->count() > 0 ? $totalValue / $products->count() : 0
            ],
            'headers' => [
                'Kode' => 'product_code',
                'Produk' => 'product_name',
                'Kategori' => 'category_name',
                'Jumlah' => 'total_quantity',
                'Nilai' => 'total_value'
            ],
            'items' => $products,
            'date' => now()
        ];

        // Handle export atau tampilkan view
        if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
            return $this->handleExport(
                $data,
                'stock-out',
                'stock_out_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
            );
        }

        return view('reports.stock-out', $data);
    }

    /**
     * Helper untuk mendapatkan data stok harian
     */
    private function getDailyStockData($query, $startDate, $endDate, $dateColumn)
    {
        $dailyData = clone $query;
        
        // Tentukan nama tabel berdasarkan dateColumn
        $detailsTable = strpos($dateColumn, 'purchases') !== false ? 'purchase_details' : 'sale_details';
        
        $dailyData = $dailyData->select(
                DB::raw("DATE_FORMAT({$dateColumn}, '%d/%m/%Y') as date"),
                DB::raw("SUM({$detailsTable}.quantity) as total_quantity"),
                DB::raw("SUM({$detailsTable}.quantity * {$detailsTable}.price) as total_value")
            )
            ->groupBy(DB::raw("DATE_FORMAT({$dateColumn}, '%d/%m/%Y')"))
            ->orderBy(DB::raw("DATE_FORMAT({$dateColumn}, '%Y-%m-%d')"))
            ->get();

        return [
            'labels' => $dailyData->pluck('date')->toArray(),
            'quantity' => $dailyData->pluck('total_quantity')->toArray(),
            'value' => $dailyData->pluck('total_value')->toArray()
        ];
    }

    /**
     * Helper untuk mendapatkan data stok mingguan
     */
    private function getWeeklyStockData($query, $startDate, $endDate, $dateColumn)
    {
        $weeklyData = clone $query;
        
        // Tentukan nama tabel berdasarkan dateColumn
        $detailsTable = strpos($dateColumn, 'purchases') !== false ? 'purchase_details' : 'sale_details';
        
        $weeklyData = $weeklyData->select(
                DB::raw("CONCAT('Minggu ', WEEK({$dateColumn})) as week"),
                DB::raw("SUM({$detailsTable}.quantity) as total_quantity"),
                DB::raw("SUM({$detailsTable}.quantity * {$detailsTable}.price) as total_value")
            )
            ->groupBy(DB::raw("WEEK({$dateColumn})"))
            ->orderBy(DB::raw("WEEK({$dateColumn})"))
            ->get();

        return [
            'labels' => $weeklyData->pluck('week')->toArray(),
            'quantity' => $weeklyData->pluck('total_quantity')->toArray(),
            'value' => $weeklyData->pluck('total_value')->toArray()
        ];
    }

    /**
     * Helper untuk mendapatkan data stok bulanan
     */
    private function getMonthlyStockData($query, $startDate, $endDate, $dateColumn)
    {
        $monthlyData = clone $query;
        
        // Tentukan nama tabel berdasarkan dateColumn
        $detailsTable = strpos($dateColumn, 'purchases') !== false ? 'purchase_details' : 'sale_details';
        
        $monthlyData = $monthlyData->select(
                DB::raw("DATE_FORMAT({$dateColumn}, '%m/%Y') as month"),
                DB::raw("SUM({$detailsTable}.quantity) as total_quantity"),
                DB::raw("SUM({$detailsTable}.quantity * {$detailsTable}.price) as total_value")
            )
            ->groupBy(DB::raw("DATE_FORMAT({$dateColumn}, '%m/%Y')"))
            ->orderBy(DB::raw("DATE_FORMAT({$dateColumn}, '%Y-%m')"))
            ->get();

        return [
            'labels' => $monthlyData->pluck('month')->toArray(),
            'quantity' => $monthlyData->pluck('total_quantity')->toArray(),
            'value' => $monthlyData->pluck('total_value')->toArray()
        ];
    }

    public function profitLoss(Request $request)
    {
        try {
            // Validasi input date
            $startDate = $request->get('start_date')
                ? Carbon::parse($request->get('start_date'))->startOfDay()
                : Carbon::now()->startOfMonth();

            $endDate = $request->get('end_date')
                ? Carbon::parse($request->get('end_date'))->endOfDay()
                : Carbon::now()->endOfDay();

            // Validasi range tanggal
            if ($startDate > $endDate) {
                return back()->with('error', 'Start date cannot be later than end date');
            }

            // Optimasi query Sales dengan select specific columns
            $sales = Sale::query()
                ->select([
                    'id',
                    'invoice_number',
                    'total_amount',
                    'created_at',
                    'user_id'
                ])
                ->with([
                    'user:id,name',  // Select specific columns
                ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNull('deleted_at')
                ->get();

            // Optimasi query Purchases dengan select specific columns
            $purchases = Purchase::query()
                ->select([
                    'id',
                    'invoice_number',
                    'total_amount',
                    'created_at',
                    'supplier_id'
                ])
                ->with([
                    'supplier:id,name',  // Select specific columns
                ])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNull('deleted_at')
                ->get();

            // Hitung total menggunakan query builder untuk performa lebih baik
            $totalSales = $sales->sum('total_amount');
            $totalPurchases = $purchases->sum('total_amount');
            $grossProfit = $totalSales - $totalPurchases;

            // Prepare data untuk view dengan cara yang lebih efisien
            $items = collect()
                ->concat($sales->map(function ($sale) {
                    return [
                        'date' => $sale->created_at->format('Y-m-d'),
                        'type' => 'Sale',
                        'reference' => $sale->invoice_number,
                        'amount' => $sale->total_amount
                    ];
                }))
                ->concat($purchases->map(function ($purchase) {
                    return [
                        'date' => $purchase->created_at->format('Y-m-d'),
                        'type' => 'Purchase',
                        'reference' => $purchase->invoice_number,
                        'amount' => -$purchase->total_amount
                    ];
                }))
                ->sortByDesc('date');

            $data = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalSales' => $totalSales,
                'totalPurchases' => $totalPurchases,
                'grossProfit' => $grossProfit,
                'sales' => $sales,
                'purchases' => $purchases,
                'summary' => [
                    'total_sales' => $totalSales,
                    'total_purchases' => $totalPurchases,
                    'gross_profit' => $grossProfit,
                    'total_transactions' => $sales->count() + $purchases->count()
                ],
                'headers' => [
                    'Date' => 'date',
                    'Type' => 'type',
                    'Reference' => 'reference',
                    'Amount' => 'amount'
                ],
                'items' => $items,
                'date' => now()
            ];

            // Always return view unless explicitly requesting export
            if ($request->get('type') === 'pdf' || $request->get('type') === 'excel') {
                return $this->handleExport(
                    $data,
                    'profit-loss',
                    'profit_loss_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
                );
            }

            return view('reports.profit-loss', $data);
        } catch (\Exception $e) {
            Log::error('Error in profit loss report: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the report');
        }
    }
}
