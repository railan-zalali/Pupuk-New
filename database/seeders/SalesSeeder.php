<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data from database
        $customers = Customer::all();
        $products = Product::with('productUnits')->get();
        $users = User::all();
        
        if ($customers->isEmpty() || $products->isEmpty() || $users->isEmpty()) {
            $this->command->error('Please ensure you have customers, products, and users in the database before running this seeder.');
            return;
        }

        $paymentMethods = ['cash', 'transfer', 'credit'];
        $vehicleTypes = ['Motor', 'Mobil', 'Truk', 'Pick Up'];
        $statuses = ['draft', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['paid', 'partial', 'unpaid'];

        $this->command->info('Creating 100 sales transactions...');

        for ($i = 1; $i <= 100; $i++) {
            $customer = $customers->random();
            $user = $users->random();
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $vehicleType = $vehicleTypes[array_rand($vehicleTypes)];
            $status = $statuses[array_rand($statuses)];
            
            // Generate random date within last 6 months
            $date = Carbon::now()->subDays(rand(0, 180));
            
            // Create sale
            $sale = Sale::create([
                'invoice_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'date' => $date,
                'total_amount' => 0, // Will be calculated after adding items
                'discount' => rand(0, 50000),
                'paid_amount' => 0, // Will be calculated based on payment method
                'down_payment' => 0,
                'change_amount' => 0,
                'payment_method' => $paymentMethod,
                'vehicle_type' => $vehicleType,
                'vehicle_number' => $this->generateVehicleNumber(),
                'payment_status' => $this->getPaymentStatus($paymentMethod),
                'status' => $status,
                'remaining_amount' => 0,
                'due_date' => $paymentMethod === 'credit' ? $date->copy()->addDays(30) : null,
                'notes' => 'Test transaction #' . $i,
                'draft_id' => null,
                'is_draft_processed' => false
            ]);

            // Add 1-5 random products to each sale
            $numItems = rand(1, 5);
            $totalAmount = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $productUnit = $product->productUnits->isNotEmpty() ? $product->productUnits->random() : null;
                
                if (!$productUnit) continue;

                $quantity = rand(1, 10);
                $price = $productUnit->selling_price ?? rand(10000, 500000);
                $subtotal = $quantity * $price;
                $totalAmount += $subtotal;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $quantity * ($productUnit->conversion_factor ?? 1),
                    'price' => $price,
                    'subtotal' => $subtotal
                ]);
            }

            // Update sale with calculated amounts
            $finalAmount = $totalAmount - $sale->discount;
            $paidAmount = $this->calculatePaidAmount($finalAmount, $paymentMethod);
            $remainingAmount = $finalAmount - $paidAmount;

            $sale->update([
                'total_amount' => $finalAmount,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'change_amount' => $paymentMethod === 'cash' && $paidAmount > $finalAmount ? $paidAmount - $finalAmount : 0
            ]);

            if ($i % 10 == 0) {
                $this->command->info("Created {$i} sales transactions...");
            }
        }

        $this->command->info('Successfully created 100 sales transactions with all payment methods!');
        
        // Display summary
        $cashCount = Sale::where('payment_method', 'cash')->count();
        $transferCount = Sale::where('payment_method', 'transfer')->count();
        $creditCount = Sale::where('payment_method', 'credit')->count();
        
        $this->command->info("Summary:");
        $this->command->info("- Cash transactions: {$cashCount}");
        $this->command->info("- Transfer transactions: {$transferCount}");
        $this->command->info("- Credit transactions: {$creditCount}");
    }

    private function generateVehicleNumber(): string
    {
        $letters = ['B', 'D', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'W', 'Z'];
        $letter1 = $letters[array_rand($letters)];
        $numbers = rand(1000, 9999);
        $letter2 = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        
        return $letter1 . ' ' . $numbers . ' ' . $letter2;
    }

    private function getPaymentStatus(string $paymentMethod): string
    {
        switch ($paymentMethod) {
            case 'cash':
            case 'transfer':
                return rand(0, 1) ? 'paid' : 'paid'; // Mostly paid for cash/transfer
            case 'credit':
                $statuses = ['paid', 'partial', 'unpaid'];
                return $statuses[array_rand($statuses)];
            default:
                return 'paid';
        }
    }

    private function calculatePaidAmount(float $totalAmount, string $paymentMethod): float
    {
        switch ($paymentMethod) {
            case 'cash':
                // Cash is usually paid in full, sometimes with overpayment
                return $totalAmount + rand(0, 50000);
            case 'transfer':
                // Transfer is usually exact amount
                return $totalAmount;
            case 'credit':
                // Credit can be partial payment or no payment
                $paymentTypes = [0, $totalAmount * 0.3, $totalAmount * 0.5, $totalAmount];
                return $paymentTypes[array_rand($paymentTypes)];
            default:
                return $totalAmount;
        }
    }
}
