<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use App\Imports\ProductsImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier', 'units'])
            ->orderBy('name', 'asc')
            ->paginate(10);

        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = UnitOfMeasure::all();

        return view('products.index', compact('products', 'categories', 'suppliers', 'units'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = UnitOfMeasure::all();
        $productCode = 'PRD' . date('Ymd') . rand(1000, 9999);

        return view('products.create', compact('categories', 'suppliers', 'units', 'productCode'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:products,code,NULL,id,deleted_at,NULL',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'required|integer|min:0',
                'units' => 'required|array|min:1',
                'units.*.unit_id' => 'required|exists:unit_of_measures,id',
                'units.*.conversion_factor' => 'required|numeric|min:1',
                'units.*.purchase_price' => 'required|numeric|min:0',
                'units.*.selling_price' => 'required|numeric|min:0',
                'units.*.expire_date' => 'nullable|date',
                'units.*.is_default' => 'nullable'
            ]);

            // Process image if uploaded
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Make sure the directory exists
                Storage::makeDirectory('public/products');

                // Store the image with a consistent path
                $image->storeAs('products', $imageName, 'public');
                $validated['image_path'] = 'products/' . $imageName;
            }

            // Get base unit values for product table
            $baseUnitIndex = null;
            foreach ($request->units as $index => $unit) {
                if (isset($unit['is_default']) && $unit['is_default']) {
                    $baseUnitIndex = $index;
                    break;
                }
            }

            // If no default unit is specified, use the first one
            if ($baseUnitIndex === null) {
                $baseUnitIndex = 0;
                $request->units[0]['is_default'] = true;
            }

            // Create product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'supplier_id' => $validated['supplier_id'],
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'image_path' => $validated['image_path'] ?? null,
                'purchase_price' => $request->units[$baseUnitIndex]['purchase_price'],
                'selling_price' => $request->units[$baseUnitIndex]['selling_price'],
                'stock' => $validated['stock'],
                'min_stock' => $validated['min_stock'],
                'expire_date' => $request->units[$baseUnitIndex]['expire_date'] ?? null,
            ]);

            // Create product units
            foreach ($request->units as $unit) {
                ProductUnit::create([
                    'product_id' => $product->id,
                    'unit_id' => $unit['unit_id'],
                    'conversion_factor' => $unit['conversion_factor'],
                    'purchase_price' => $unit['purchase_price'],
                    'selling_price' => $unit['selling_price'],
                    'expire_date' => $unit['expire_date'] ?? null,
                    'is_default' => isset($unit['is_default']) && $unit['is_default'],
                ]);
            }

            // Create initial stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $validated['stock'],
                'before_stock' => 0,
                'after_stock' => $validated['stock'],
                'reference_type' => 'initial',
                'reference_id' => $product->id,
                'notes' => 'Initial stock'
            ]);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function storeBatch(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'products' => 'required|array|min:1',
                'products.*.category_id' => 'required|exists:categories,id',
                'products.*.name' => 'required|string|max:255',
                'products.*.purchase_price' => 'required|numeric|min:0',
                'products.*.selling_price' => 'required|numeric|min:0',
                'products.*.stock' => 'required|integer|min:0',
                'products.*.min_stock' => 'required|integer|min:0',
                'products.*.unit_id' => 'required|exists:unit_of_measures,id',
                'products.*.description' => 'nullable|string',
            ]);

            // Log untuk debug
            Log::info('Request batch product masuk', [
                'supplier_id' => $request->supplier_id,
                'is_batch' => $request->is_batch,
                'products_count' => count($request->products),
                'request_data' => $request->all()
            ]);

            $today = date('Ymd');
            $baseCode = 'PRD-' . $today . '-';

            // Find the highest number for today's products
            $lastProduct = Product::withTrashed()->where('code', 'like', $baseCode . '%')
                ->orderBy('code', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastProduct) {
                $lastNumber = (int) substr($lastProduct->code, -4);
            }

            $createdProducts = [];
            $supplierId = $request->input('supplier_id');

            // Ambil supplier untuk log
            $supplier = Supplier::find($supplierId);
            Log::info('Processing batch dengan supplier', [
                'supplier_id' => $supplierId,
                'supplier_name' => $supplier ? $supplier->name : 'Unknown'
            ]);

            foreach ($request->products as $index => $productData) {
                // Generate new code for each product
                $newNumber = ++$lastNumber;
                $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                // Make sure it's unique
                while (Product::withTrashed()->where('code', $productCode)->exists()) {
                    $newNumber++;
                    $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                }

                // Log produk yang sedang dibuat
                Log::info('Membuat produk batch #' . ($index + 1), [
                    'product_name' => $productData['name'],
                    'product_code' => $productCode,
                    'category_id' => $productData['category_id'],
                    'unit_id' => $productData['unit_id']
                ]);

                // Create the product
                $product = Product::create([
                    'category_id' => $productData['category_id'],
                    'supplier_id' => $supplierId,
                    'name' => $productData['name'],
                    'code' => $productCode,
                    'description' => $productData['description'] ?? null,
                    'purchase_price' => $productData['purchase_price'],
                    'selling_price' => $productData['selling_price'],
                    'stock' => $productData['stock'],
                    'min_stock' => $productData['min_stock']
                ]);

                Log::info('Produk batch berhasil dibuat', [
                    'product_id' => $product->id,
                    'product_name' => $product->name
                ]);

                // Create default product unit
                $productUnit = ProductUnit::create([
                    'product_id' => $product->id,
                    'unit_id' => $productData['unit_id'],
                    'conversion_factor' => 1, // Base unit
                    'purchase_price' => $productData['purchase_price'],
                    'selling_price' => $productData['selling_price'],
                    'is_default' => true
                ]);

                Log::info('Unit produk batch berhasil dibuat', [
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id
                ]);

                // Record initial stock movement if needed
                if ($productData['stock'] > 0) {
                    $stockMovement = StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $productData['stock'],
                        'before_stock' => 0,
                        'after_stock' => $productData['stock'],
                        'reference_type' => 'initial',
                        'reference_id' => $product->id,
                        'notes' => 'Initial stock'
                    ]);

                    Log::info('Stock movement untuk batch berhasil dibuat', [
                        'stock_movement_id' => $stockMovement->id,
                        'quantity' => $stockMovement->quantity
                    ]);
                }

                $createdProducts[] = $product;
            }

            DB::commit();
            Log::info('Batch insert berhasil', [
                'products_count' => count($createdProducts)
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', count($createdProducts) . ' produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat melakukan batch insert: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Gagal menambahkan produk batch: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product = Product::with(['category', 'stockMovements' => function ($query) {
            $query->latest();
        }])->findOrFail($product->id);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = UnitOfMeasure::all();
        $supplierId = $product->supplier_id;

        // Load product units with their relationships
        $product->load(['productUnits' => function ($query) {
            $query->orderBy('is_default', 'desc');
        }]);

        return view('products.edit', compact('product', 'categories', 'suppliers', 'units', 'supplierId'));
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'units' => 'required|array|min:1',
            'units.*.unit_id' => 'required|exists:unit_of_measures,id',
            'units.*.conversion_factor' => 'required|numeric|min:1',
            'units.*.purchase_price' => 'required|numeric|min:0',
            'units.*.selling_price' => 'required|numeric|min:0',
            'units.*.expire_date' => 'nullable|date',
            'units.*.is_default' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update product data
            $product->update([
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image_path) {
                    Storage::delete($product->image_path);
                }

                $imagePath = $request->file('image')->store('products', 'public');
                $product->update(['image_path' => $imagePath]);
            }

            // Handle product units
            $existingUnitIds = [];
            $hasDefaultUnit = false;

            foreach ($request->units as $unitData) {
                $unitData['is_default'] = isset($unitData['is_default']) ? true : false;

                if ($unitData['is_default']) {
                    $hasDefaultUnit = true;
                }

                if (isset($unitData['id'])) {
                    // Update existing unit
                    $productUnit = $product->productUnits()->find($unitData['id']);
                    if ($productUnit) {
                        $productUnit->update([
                            'unit_id' => $unitData['unit_id'],
                            'conversion_factor' => $unitData['conversion_factor'],
                            'purchase_price' => $unitData['purchase_price'],
                            'selling_price' => $unitData['selling_price'],
                            'expire_date' => $unitData['expire_date'],
                            'is_default' => $unitData['is_default'],
                        ]);
                        $existingUnitIds[] = $productUnit->id;
                    }
                } else {
                    // Create new unit
                    $productUnit = $product->productUnits()->create([
                        'unit_id' => $unitData['unit_id'],
                        'conversion_factor' => $unitData['conversion_factor'],
                        'purchase_price' => $unitData['purchase_price'],
                        'selling_price' => $unitData['selling_price'],
                        'expire_date' => $unitData['expire_date'],
                        'is_default' => $unitData['is_default'],
                    ]);
                    $existingUnitIds[] = $productUnit->id;
                }
            }

            // If no default unit is set, set the first unit as default
            if (!$hasDefaultUnit && count($request->units) > 0) {
                $firstUnit = $product->productUnits()->whereIn('id', $existingUnitIds)->first();
                if ($firstUnit) {
                    $firstUnit->update(['is_default' => true]);
                }
            }

            // Delete units that are no longer present
            $product->productUnits()->whereNotIn('id', $existingUnitIds)->delete();

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage());
        }
    }

    public function duplicate(Product $product)
    {
        $categories = Category::all();
        $units = UnitOfMeasure::all();
        $suppliers = Supplier::all();

        // Generate kode produk baru
        $today = date('Ymd');
        $baseCode = 'PRD-' . $today . '-';
        $lastProduct = Product::withTrashed()
            ->where('code', 'like', $baseCode . '%')
            ->orderBy('code', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->code, -4);
        }

        $newNumber = $lastNumber + 1;
        $newCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Duplikasi data produk
        $duplicatedProduct = $product->replicate();
        $duplicatedProduct->name = 'Copy of ' . $product->name;
        $duplicatedProduct->code = $newCode;
        $duplicatedProduct->stock = 0; // Reset stock
        $duplicatedProduct->image_path = null; // Reset image

        return view('products.create', [
            'productTemplate' => $duplicatedProduct,
            'categories' => $categories,
            'units' => $units,
            'suppliers' => $suppliers,
            'productCode' => $newCode,
            'productUnits' => $product->productUnits
        ]);
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $beforeStock = $product->stock;
        $quantity = $validated['quantity'];

        if ($validated['adjustment_type'] === 'add') {
            $product->increment('stock', $quantity);
            $type = 'in';
        } else {
            if ($product->stock < $quantity) {
                return back()->with('error', 'Insufficient stock');
            }
            $product->decrement('stock', $quantity);
            $type = 'out';
        }

        // Record stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'before_stock' => $beforeStock,
            'after_stock' => $product->stock,
            'reference_type' => 'adjustment',
            'reference_id' => $product->id,
            'notes' => $validated['notes'] ?? null
        ]);

        return back()->with('success', 'Stock updated successfully');
    }

    public function deleteImage(Product $product)
    {
        try {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                $product->update(['image_path' => null]);
                return back()->with('success', 'Product image removed successfully');
            }

            return back()->with('error', 'No image to delete');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting product image: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete image. Please try again.');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('query');

        $products = Product::query()
            ->with('category')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
    public function getUnits(Product $product)
    {
        return response()->json([
            'stock' => $product->stock,
            'units' => $product->productUnits->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'unit_name' => $unit->unit->name,
                    'unit_abbreviation' => $unit->unit->abbreviation,
                    'selling_price' => $unit->selling_price,
                    'conversion_factor' => $unit->conversion_factor,
                    'is_default' => $unit->is_default
                ];
            })
        ]);
    }

    /**
     * Menampilkan form upload untuk import produk
     */
    public function importForm()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = UnitOfMeasure::all();

        return view('products.import', compact('categories', 'suppliers', 'units'));
    }

    /**
     * Memproses file import produk
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('file'));

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Download template Excel untuk import produk
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\ProductsTemplateExport(), 'template_import_produk.xlsx');
    }
}
