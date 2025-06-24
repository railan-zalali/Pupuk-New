<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
