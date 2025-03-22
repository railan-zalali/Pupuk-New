<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Traits\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use ReportExport;

    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $sales = Sale::with(['user', 'customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($request->payment_method, function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->latest()
            ->get();

        $data = [
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'total_sales' => $sales->count(),
                'total_amount' => $sales->sum('total_amount'),
                'average_sale' => $sales->avg('total_amount'),
                'payment_methods' => $sales->groupBy('payment_method')
                    ->map(fn($group) => $group->count())
            ],
            'headers' => [
                'Date' => 'date',
                'Invoice' => 'invoice_number',
                'Customer' => 'customer_name',
                'Amount' => 'total_amount',
                'Payment' => 'payment_method'
            ],
            'items' => $sales
        ];

        return $this->handleExport(
            $data,
            'sales',
            'sales_report_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d')
        );
    }

    public function stock()
    {
        $products = Product::with('category')
            ->withSum('purchaseDetails as total_purchased', 'quantity')
            ->withSum('saleDetails as total_sold', 'quantity')
            ->get()
            ->map(function ($product) {
                $product->stock_value = $product->stock * $product->purchase_price;
                return $product;
            });

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

            // Handle export atau tampilkan view
            if ($request->has('type')) {
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
