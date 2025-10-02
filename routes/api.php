<?php

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ProductController as ApiProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Sales API Routes
Route::prefix('sales')->group(function () {
    Route::get('products', [SaleController::class, 'products']);
    Route::post('/', [SaleController::class, 'store']);
});

// Purchase API Routes
Route::prefix('purchases')->group(function () {
    Route::get('products', [\App\Http\Controllers\Api\PurchaseController::class, 'products']);
    Route::get('products/categories', [\App\Http\Controllers\Api\PurchaseController::class, 'categories']);
});

// Enhanced Product API Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ApiProductController::class, 'index']);
    Route::get('/search', [ApiProductController::class, 'search']);
    Route::get('/popular', [ApiProductController::class, 'popular']);
    Route::get('/categories', [ApiProductController::class, 'categories']);
    Route::get('/statistics', [ApiProductController::class, 'statistics']);
    Route::post('/clear-cache', [ApiProductController::class, 'clearCache']);
    Route::get('/{product}/batches', [ProductController::class, 'getBatches']);
    Route::get('/{id}', [ApiProductController::class, 'show']);
});

// // API endpoint untuk mendapatkan semua produk
// Route::get('/products', function () {
//     $products = Product::with(['category', 'productUnits'])->get()->map(function ($product) {
//         $defaultUnit = $product->productUnits->where('is_default', 1)->first();
//         return [
//             'id' => $product->id,
//             'name' => $product->name,
//             'code' => $product->code,
//             'description' => $product->description,
//             'image_path' => $product->image_path,
//             'category_id' => $product->category_id,
//             'category_name' => $product->category ? $product->category->name : null,
//             'stock' => $product->stock,
//             'selling_price' => $defaultUnit ? $defaultUnit->selling_price : 0,
//         ];
//     });

//     return response()->json($products);
// });

// // API endpoint untuk mendapatkan semua kategori
// Route::get('/categories', function () {
//     $categories = Category::all();
//     return response()->json($categories);
// });

// // API endpoint untuk mencari produk berdasarkan barcode
// Route::get('/products/find-by-barcode/{barcode}', function ($barcode) {
//     $product = Product::where('code', $barcode)
//         ->with(['category', 'productUnits'])
//         ->first();

//     if (!$product) {
//         return response()->json(['error' => 'Produk tidak ditemukan'], 404);
//     }

//     $defaultUnit = $product->productUnits->where('is_default', 1)->first();

//     return response()->json([
//         'id' => $product->id,
//         'name' => $product->name,
//         'code' => $product->code,
//         'description' => $product->description,
//         'image_path' => $product->image_path,
//         'category_id' => $product->category_id,
//         'category_name' => $product->category ? $product->category->name : null,
//         'stock' => $product->stock,
//         'selling_price' => $defaultUnit ? $defaultUnit->selling_price : 0,
//     ]);
// })->middleware('web');

// Route::get('/products/{product}/units', function (Product $product) {
//     return response()->json([
//         'stock' => $product->stock,
//         'units' => $product->productUnits->map(function ($unit) {
//             return [
//                 'id' => $unit->id,
//                 'unit_name' => $unit->unit->name,
//                 'unit_abbreviation' => $unit->unit->abbreviation,
//                 'selling_price' => $unit->selling_price,
//                 'conversion_factor' => $unit->conversion_factor,
//                 'is_default' => $unit->is_default
//             ];
//         })
//     ]);
// });
