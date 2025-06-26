<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth'])
//     ->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


//     Route::middleware(['can:manage-users'])->group(function () {
//         Route::resource('users', UserController::class);
//         Route::resource('roles', RoleController::class);
//     });
//     Route::middleware(['role:admin'])->group(function () {
//         Route::resource('users', UserController::class);
//         Route::resource('roles', RoleController::class);
//     });

// Route dengan permission
//     Route::middleware('permission:manage-products')->group(function () {
//         Route::resource('products', ProductController::class);
//     });
// });

// Route::resource('categories', CategoryController::class);

// Route untuk manajemen produk
// Route::resource('products', ProductController::class);
// Route::get('/products', [ProductController::class, 'index'])->name('products');
// Route tambahan untuk update stok
// Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])
// ->name('products.updateStock');



// Route::resource('suppliers', SupplierController::class);
// Route::resource('purchases', PurchaseController::class);

// Route::resource('sales', SaleController::class);
// Route::get('/products/{product}/get', [SaleController::class, 'getProduct'])->name('products.get');
// Route::get('sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');


// Route::resource('customers', CustomerController::class);
// Route::get('customers-search', [CustomerController::class, 'search'])->name('customers.search');

// Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
// Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
// Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
// Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
// Route::get('/reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.export.sales');



Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stock-details', [DashboardController::class, 'dailyStockDetails'])->name('stock.details');
    Route::get('/weekly-stock-details', [DashboardController::class, 'weeklyStockDetails'])->name('weekly.stock.details');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes (User dan Role management)
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::resource('users', UserController::class);
    //     Route::resource('roles', RoleController::class);
    // });
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    // Resource routes dengan permission
    Route::middleware(['permission:manage-products'])->group(function () {
        // Rute untuk import produk
        Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import');
        Route::post('products/import-process', [ProductController::class, 'importProcess'])->name('products.import-process');
        Route::get('products/template/download', [ProductController::class, 'downloadTemplate'])->name('products.template.download');
        Route::get('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::get('/products/{product}/units', [ProductController::class, 'getUnits'])->name('products.getWithUnits');
        Route::get('/products/create-batch', [ProductController::class, 'createBatch'])->name('products.create-batch');
        Route::post('/products/store-batch', [ProductController::class, 'storeBatch'])->name('products.store-batch');
        Route::resource('categories', CategoryController::class);

        Route::post('/products/{product}/remove-image', [ProductController::class, 'deleteImage'])
            ->name('products.deleteImage');

        Route::resource('products', ProductController::class);

        Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');
    });

    Route::middleware(['permission:manage-suppliers'])->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });

    Route::middleware(['permission:manage-customers'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::get('customers/data', [CustomerController::class, 'data'])->name('customers.data');
        Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::get('customers-search', [CustomerController::class, 'search'])
            ->name('customers.search');
        Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
        Route::get('/customers/template/download', [CustomerController::class, 'downloadTemplate'])->name('customers.template.download');
        Route::get('/customers/{customer}/history', [CustomerController::class, 'getCustomerHistory'])->name('customers.history');
    });

    Route::middleware(['permission:manage-purchases'])->group(function () {
        Route::get('purchases/credit', [PurchaseController::class, 'creditPurchases'])->name('purchases.credit');
        Route::get('purchases/drafts', [PurchaseController::class, 'drafts'])->name('purchases.drafts');
        Route::put('/purchases/{purchase}/complete-draft', [PurchaseController::class, 'completeDraft'])->name('purchases.complete_draft');
        Route::get('purchases/products-by-supplier/{supplierId}', [PurchaseController::class, 'getProductsBySupplier']);
        Route::get('purchases/product-units/{productId}', [PurchaseController::class, 'getProductUnits']);
        Route::get('purchases/search-products', [PurchaseController::class, 'searchProducts']);
        Route::get('purchases/find-by-barcode', [PurchaseController::class, 'findByBarcode']);
        Route::resource('purchases', PurchaseController::class);
        Route::get('/purchases/products-by-supplier/{supplier}', [PurchaseController::class, 'getProductsBySupplier'])
            ->name('purchases.products-by-supplier');
        Route::get('/purchases/{purchase}/receipt', [PurchaseController::class, 'createReceipt'])
            ->name('purchases.receipt');
        Route::post('/purchases/{purchase}/receipt', [PurchaseController::class, 'storeReceipt'])
            ->name('purchases.storeReceipt');
    });


    Route::middleware(['permission:manage-sales'])->group(function () {
        Route::post('/sales/auto-save-draft', [SaleController::class, 'autoSaveDraft'])->name('sales.auto-save-draft');
        Route::get('sales/credit', [SaleController::class, 'creditSales'])->name('sales.credit');
        Route::get('sales/drafts', [SaleController::class, 'drafts'])->name('sales.drafts');
        Route::get('/sales/{sale}/complete-draft', [SaleController::class, 'completeDraft'])
            ->name('sales.complete-draft');


        Route::get('/products/{product}/get', [SaleController::class, 'getProduct'])->name('products.get');

        // Invoice routes
        Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
        Route::get('/sales/{sale}/invoice-seeds', [SaleController::class, 'invoiceSeeds'])->name('sales.invoice-seeds');
        Route::get('/sales/{sale}/delivery-note', [SaleController::class, 'deliveryNote'])->name('sales.delivery-note');

        Route::post('/sales/{sale}/pay', [SaleController::class, 'payCredit'])->name('sales.pay');
        Route::get('/sales/{sale}/details', [SaleController::class, 'getSaleDetails'])->name('sales.details');

        Route::resource('sales', SaleController::class);
    });

    // Report routes
    Route::middleware(['permission:access-reports'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
        Route::get('/reports/accounts', [ReportController::class, 'accounts'])->name('reports.accounts');
        Route::get('/reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.export.sales');
    });


    // Route::resource('cash-book', CashBookController::class);
});
require __DIR__ . '/auth.php';
Route::get('search/products', [ProductController::class, 'search'])->name('products.search');

Route::get('products/find-by-barcode', [ProductController::class, 'findByBarcode'])->middleware(['auth']);
