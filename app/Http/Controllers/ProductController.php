<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderByRaw('CASE WHEN stock < min_stock THEN 0 ELSE 1 END')
            ->orderBy('stock')
            ->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        // Generate a unique product code
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

        // Generate new code
        $newNumber = $lastNumber + 1;
        $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Make sure it's unique
        while (Product::withTrashed()->where('code', $productCode)->exists()) {
            $newNumber++;
            $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return view('products.create', compact('categories', 'productCode'));
    }

    public function store(Request $request)
    {
        // Re-check if the code is unique before validating
        $code = $request->input('code');
        if (Product::withTrashed()->where('code', $code)->exists()) {
            // Generate a new unique code
            $today = date('Ymd');
            $baseCode = 'PRD-' . $today . '-';
            $lastProduct = Product::where('code', 'like', $baseCode . '%')
                ->orderBy('code', 'desc')
                ->first();

            $lastNumber = $lastProduct ? (int) substr($lastProduct->code, -4) : 0;
            $newNumber = $lastNumber + 1;
            $newCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Make sure new code is unique
            while (Product::withTrashed()->where('code', $newCode)->exists()) {
                $newNumber++;
                $newCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }

            // Replace the code in the request
            $request->merge(['code' => $newCode]);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            // 'code' => 'required|string|unique:products',
            'code' => 'required|string|unique:products,code,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0'
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

        $product = Product::create($validated);

        // Record initial stock movement
        $initialStock = $validated['stock'];
        if ($initialStock > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $initialStock,
                'before_stock' => 0,
                'after_stock' => $initialStock,
                'reference_type' => 'initial',
                'reference_id' => $product->id,
                'notes' => 'Initial stock'
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully');
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
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:products,code,' . $product->id,
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'min_stock' => 'required|integer|min:0'
            ]);

            // Create an array of fields to update
            $dataToUpdate = [
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'min_stock' => $validated['min_stock'],
            ];

            // Handle image upload separately
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Make sure the directory exists
                Storage::makeDirectory('public/products');

                // Store with consistent path format
                $image->storeAs('products', $imageName, 'public');
                $dataToUpdate['image_path'] = 'products/' . $imageName;
            }

            // Update product
            $product->update($dataToUpdate);

            return redirect()
                ->route('products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating product: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
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
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
