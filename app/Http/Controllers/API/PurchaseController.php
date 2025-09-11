<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Get products for purchase with supplier filtering
     */
    public function products(Request $request): JsonResponse
    {
        try {
            $perPage = min((int) $request->get('per_page', 12), 1000);
            $search = $request->get('search', '');
            $categoryId = $request->get('category_id');
            $supplierId = $request->get('supplier_id');
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');

            // Build query
            $query = Product::with(['category', 'suppliers']);

            // Filter by supplier if provided
            if ($supplierId) {
                $query->whereHas('suppliers', function ($q) use ($supplierId) {
                    $q->where('supplier_id', $supplierId);
                })->orWhereDoesntHave('suppliers'); // Include products not associated with any supplier
            }

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($categoryQuery) use ($search) {
                          $categoryQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Category filter
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            // Sorting
            $allowedSorts = ['name', 'code', 'stock', 'purchase_price', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            } else {
                $query->orderBy('name', 'asc');
            }

            $products = $query->paginate($perPage);

            // Transform data for frontend
            $data = $products->map(function ($product) use ($supplierId) {
                // Get supplier-specific price if available
                $supplierPrice = null;
                if ($supplierId) {
                    $supplier = $product->suppliers->where('id', $supplierId)->first();
                    if ($supplier && $supplier->pivot->purchase_price) {
                        $supplierPrice = $supplier->pivot->purchase_price;
                    }
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'description' => $product->description,
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                    'purchase_price' => $supplierPrice ?? $product->purchase_price,
                    'supplier_price' => $supplierPrice,
                    'default_price' => $product->purchase_price,
                    'is_low_stock' => $product->stock < $product->min_stock,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ] : null,
                    'suppliers' => $product->suppliers->map(function ($supplier) {
                        return [
                            'id' => $supplier->id,
                            'name' => $supplier->name,
                            'purchase_price' => $supplier->pivot->purchase_price ?? null,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => [
                    'search' => $search,
                    'category_id' => $categoryId,
                    'supplier_id' => $supplierId,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Purchase products API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat produk',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get categories for purchase filtering
     */
    public function categories(): JsonResponse
    {
        try {
            $cacheKey = 'purchase_categories_with_products';
            
            $categories = Cache::remember($cacheKey, 3600, function () {
                return Category::whereHas('products')
                    ->withCount('products')
                    ->orderBy('name')
                    ->get();
            });

            $data = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Purchase categories API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}