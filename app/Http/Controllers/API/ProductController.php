<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Get products with advanced filtering and search
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::with(['category', 'supplier'])
                ->select([
                    'id', 'name', 'code', 'description', 'category_id', 'supplier_id',
                    'purchase_price', 'selling_price', 'stock', 'min_stock',
                    'image_path', 'created_at'
                ]);

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('code', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                          $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      })
                      ->orWhereHas('supplier', function ($supplierQuery) use ($searchTerm) {
                          $supplierQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      });
                });
            }

            // Category filter
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Supplier filter
            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            // Stock filter
            if ($request->filled('stock_filter')) {
                switch ($request->stock_filter) {
                    case 'available':
                        $query->where('actual_stock', '>', 0);
                        break;
                    case 'low':
                        $query->whereRaw('actual_stock > 0 AND actual_stock <= COALESCE(min_stock, 10)');
                        break;
                    case 'out':
                        $query->where('actual_stock', '<=', 0);
                        break;
                }
            }

            // Price range filter
            if ($request->filled('min_price')) {
                $query->where('selling_price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('selling_price', '<=', $request->max_price);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            
            switch ($sortBy) {
                case 'name':
                    $query->orderBy('name', $sortOrder);
                    break;
                case 'price':
                    $query->orderBy('selling_price', $sortOrder);
                    break;
                case 'stock':
                    $query->orderBy('actual_stock', $sortOrder);
                    break;
                case 'category':
                    $query->join('categories', 'products.category_id', '=', 'categories.id')
                          ->orderBy('categories.name', $sortOrder)
                          ->select('products.*');
                    break;
                case 'created_at':
                    $query->orderBy('created_at', $sortOrder);
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }

            // Pagination
            $perPage = min($request->get('per_page', 12), 1000); // Allow up to 1000 items per page for product selector
            $products = $query->paginate($perPage);

            // Transform data for frontend
            $products->getCollection()->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name
                    ] : null,
                    'supplier_id' => $product->supplier_id,
                    'supplier' => $product->supplier ? [
                        'id' => $product->supplier->id,
                        'name' => $product->supplier->name
                    ] : null,
                    'purchase_price' => (float) $product->purchase_price,
                    'selling_price' => (float) $product->selling_price,
                    'stock' => (int) $product->actual_stock,
                    'min_stock' => (int) $product->min_stock,
                    'unit_id' => $product->unit_id,
                    'units' => $product->units->map(function ($unit) {
                        return [
                            'id' => $unit->id,
                            'name' => $unit->name,
                            'conversion_factor' => $unit->pivot->conversion_factor ?? 1
                        ];
                    }),
                    'image_path' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'is_active' => $product->is_active,
                    'stock_status' => $this->getStockStatus($product->actual_stock, $product->min_stock),
                    'formatted_price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.'),
                    'created_at' => $product->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem()
                ],
                'filters' => [
                    'search' => $request->search,
                    'category_id' => $request->category_id,
                    'supplier_id' => $request->supplier_id,
                    'stock_filter' => $request->stock_filter,
                    'min_price' => $request->min_price,
                    'max_price' => $request->max_price,
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products for autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->get('q', '');
            $limit = min($request->get('limit', 10), 20);

            if (strlen($searchTerm) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Cache search results for 5 minutes
            $cacheKey = 'product_search_' . md5($searchTerm . $limit);
            
            $products = Cache::remember($cacheKey, 300, function () use ($searchTerm, $limit) {
                return Product::with(['category'])
                    ->select(['id', 'name', 'code', 'selling_price', 'stock', 'min_stock', 'category_id', 'image_path'])
                    ->where('is_active', true)
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('code', 'LIKE', "%{$searchTerm}%")
                              ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                                  $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
                              });
                    })
                    ->orderByRaw("CASE 
                        WHEN name LIKE '{$searchTerm}%' THEN 1
                        WHEN code LIKE '{$searchTerm}%' THEN 2
                        WHEN name LIKE '%{$searchTerm}%' THEN 3
                        ELSE 4
                    END")
                    ->orderBy('name')
                    ->limit($limit)
                    ->get();
            });

            $results = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'selling_price' => (float) $product->selling_price,
                    'stock' => (int) $product->actual_stock,
                    'min_stock' => (int) $product->min_stock,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name
                    ] : null,
                    'image_path' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'stock_status' => $this->getStockStatus($product->actual_stock, $product->min_stock),
                    'formatted_price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $product = Product::with(['category', 'supplier', 'units'])
                ->where('is_active', true)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'description' => $product->category->description
                    ] : null,
                    'supplier_id' => $product->supplier_id,
                    'supplier' => $product->supplier ? [
                        'id' => $product->supplier->id,
                        'name' => $product->supplier->name
                    ] : null,
                    'purchase_price' => (float) $product->purchase_price,
                    'selling_price' => (float) $product->selling_price,
                    'stock' => (int) $product->actual_stock,
                    'min_stock' => (int) $product->min_stock,
                    'unit_id' => $product->unit_id,
                    'units' => $product->units->map(function ($unit) {
                        return [
                            'id' => $unit->id,
                            'name' => $unit->name,
                            'conversion_factor' => $unit->pivot->conversion_factor ?? 1
                        ];
                    }),
                    'image_path' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'is_active' => $product->is_active,
                    'stock_status' => $this->getStockStatus($product->actual_stock, $product->min_stock),
                    'formatted_price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.'),
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get categories for filtering
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Cache::remember('product_categories', 3600, function () {
                return Category::select(['id', 'name', 'description'])
                    ->whereHas('products')
                    ->withCount('products')
                    ->orderBy('name')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product statistics for dashboard
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = Cache::remember('product_statistics', 1800, function () {
                return [
                    'total_products' => Product::where('is_active', true)->count(),
                    'available_products' => Product::where('is_active', true)->where('actual_stock', '>', 0)->count(),
                    'low_stock_products' => Product::where('is_active', true)
                        ->whereRaw('actual_stock > 0 AND actual_stock <= COALESCE(min_stock, 10)')
                        ->count(),
                    'out_of_stock_products' => Product::where('is_active', true)->where('actual_stock', '<=', 0)->count(),
                    'total_categories' => Category::whereHas('products', function ($query) {
                        $query->where('is_active', true);
                    })->count(),
                    'average_price' => Product::where('is_active', true)->avg('selling_price'),
                    'total_stock_value' => Product::where('is_active', true)
                        ->selectRaw('SUM(actual_stock * selling_price) as total')
                        ->value('total')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular products
     */
    public function popular(Request $request): JsonResponse
    {
        try {
            $limit = min($request->get('limit', 10), 20);
            
            $products = Cache::remember('popular_products_' . $limit, 1800, function () use ($limit) {
                return Product::with(['category'])
                    ->select([
                        'products.id', 'products.name', 'products.code', 'products.selling_price', 
                        'products.stock', 'products.min_stock', 'products.category_id', 'products.image_path',
                        DB::raw('COALESCE(SUM(sale_details.quantity), 0) as total_sold')
                    ])
                    ->leftJoin('sale_details', 'products.id', '=', 'sale_details.product_id')
                    ->leftJoin('sales', function ($join) {
                        $join->on('sale_details.sale_id', '=', 'sales.id')
                             ->where('sales.created_at', '>=', now()->subDays(30));
                    })
                    ->where('products.is_active', true)
                    ->groupBy([
                        'products.id', 'products.name', 'products.code', 'products.selling_price',
                        'products.stock', 'products.min_stock', 'products.category_id', 'products.image_path'
                    ])
                    ->orderByDesc('total_sold')
                    ->orderBy('products.name')
                    ->limit($limit)
                    ->get();
            });

            $results = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'selling_price' => (float) $product->selling_price,
                    'stock' => (int) $product->actual_stock,
                    'min_stock' => (int) $product->min_stock,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name
                    ] : null,
                    'image_path' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'stock_status' => $this->getStockStatus($product->actual_stock, $product->min_stock),
                    'formatted_price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.'),
                    'total_sold' => (int) $product->total_sold
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat produk populer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock status for a product
     */
    private function getStockStatus(int $stock, ?int $minStock = null): array
    {
        $minStock = $minStock ?? 10;
        
        if ($stock <= 0) {
            return [
                'status' => 'out',
                'label' => 'Habis',
                'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
            ];
        } elseif ($stock <= $minStock) {
            return [
                'status' => 'low',
                'label' => 'Menipis',
                'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200'
            ];
        } else {
            return [
                'status' => 'available',
                'label' => 'Tersedia',
                'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200'
            ];
        }
    }

    /**
     * Clear product cache
     */
    public function clearCache(): JsonResponse
    {
        try {
            Cache::forget('product_categories');
            Cache::forget('product_statistics');
            
            // Clear search cache (pattern-based)
            $cacheKeys = Cache::getRedis()->keys('*product_search_*');
            if (!empty($cacheKeys)) {
                Cache::getRedis()->del($cacheKeys);
            }
            
            $popularKeys = Cache::getRedis()->keys('*popular_products_*');
            if (!empty($popularKeys)) {
                Cache::getRedis()->del($popularKeys);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache produk berhasil dibersihkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }
}