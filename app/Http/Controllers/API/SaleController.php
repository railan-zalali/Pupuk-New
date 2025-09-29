<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Services\FifoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Get products for sales API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function products()
    {
        try {
            $products = Product::with(['category', 'productUnits.unit'])
                ->where('actual_stock', '>', 0)
                ->get()
                ->map(function ($product) {
                    $units = $product->productUnits->map(function ($unit) {
                        return [
                            'id' => $unit->id,
                            'unit_id' => $unit->unit_id,
                            'unit_name' => $unit->unit->name ?? 'Unknown',
                            'conversion_factor' => $unit->conversion_factor,
                            'selling_price' => $unit->selling_price,
                            'is_default' => $unit->is_default,
                        ];
                    });
                    
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'code' => $product->code,
                        'description' => $product->description,
                        'image_path' => $product->image_path,
                        'category_id' => $product->category_id,
                        'category_name' => $product->category ? $product->category->name : null,
                        'stock' => $product->actual_stock,
                        'units' => $units,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('API Products Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new sale via API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'customer_id' => 'nullable|exists:customers,id',
                'customer_name' => 'nullable|string|max:255',
                'payment_method' => 'required|in:cash,credit,transfer',
                'payment_status' => 'required|in:paid,unpaid,partial',
                'paid_amount' => 'required_if:payment_status,paid,partial|numeric|min:0',
                'due_date' => 'required_if:payment_status,unpaid,partial|date',
                'notes' => 'nullable|string',
                'status' => 'required|in:completed,draft',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.unit_id' => 'required|exists:product_units,id',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Begin transaction
            DB::beginTransaction();

            // Determine if saving as draft
            $savingAsDraft = $request->status === 'draft';

            // Handle customer
            $customerId = $request->customer_id;
            if (!$customerId && $request->customer_name) {
                // Create new customer if name provided but no ID
                $customer = Customer::create([
                    'nama' => $request->customer_name,
                    'alamat' => $request->customer_address ?? null,
                    'telepon' => $request->customer_phone ?? null,
                ]);
                $customerId = $customer->id;
            }

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'date' => Carbon::now(),
                'customer_id' => $customerId,
                'user_id' => auth()->check() ? auth()->id() : null,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'paid_amount' => $request->paid_amount ?? 0,
                'due_date' => $request->due_date ?? null,
                'notes' => $request->notes,
                'status' => $savingAsDraft ? 'draft' : 'completed',
            ]);

            // Create sale details
            foreach ($request->items as $item) {
                $productUnit = ProductUnit::findOrFail($item['unit_id']);
                $quantity = $item['quantity'];
                $price = $item['price'];
                $product = Product::findOrFail($item['product_id']);
                $baseQuantity = $quantity * $productUnit->conversion_factor;

                // Stock validation for completed transactions
                if (!$savingAsDraft) {
                    // Stock validation
                    if ($baseQuantity > $product->actual_stock) {
                        return response()->json([
                            'success' => false,
                            'message' => "Stok tidak cukup untuk produk: {$product->name}. Stok tersedia: {$product->actual_stock}, diminta: {$baseQuantity}"
                        ], 400);
                    }
                }

                $sale->saleDetails()->create([
                    'product_id' => $item['product_id'],
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price,
                ]);

                // Update stock using FIFO for completed transactions
                if (!$savingAsDraft) {
                    // Use FIFO Service to reduce stock
                    $fifoService = new FifoService();
                    $fifoService->reduceStock(
                        $item['product_id'],
                        $baseQuantity,
                        'sale',
                        $sale->id,
                        'Product sale'
                    );
                } else {
                    // For drafts, record draft_out movement but don't reduce physical stock
                    $product->stockMovements()->create([
                        'quantity' => $baseQuantity,
                        'type' => 'draft_out',
                        'reference_type' => 'App\Models\Sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Draft sale'
                    ]);
                }
            }

            DB::commit();

            // Load relationships for response
            $sale->load(['saleDetails.product', 'saleDetails.productUnit.unit', 'customer']);

            return response()->json([
                'status' => 'success',
                'message' => $savingAsDraft ? 'Draft saved successfully' : 'Sale completed successfully',
                'data' => $sale
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Sale Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process sale: ' . $e->getMessage()
            ], 500);
        }
    }
}