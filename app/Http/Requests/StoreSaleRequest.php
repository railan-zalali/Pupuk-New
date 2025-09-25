<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,transfer,credit',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:20',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'due_date' => 'required_if:payment_method,credit|nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'draft_id' => 'nullable|exists:sales,id',
            
            // Sale items validation
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_unit_id' => 'required|exists:product_units,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.batch_id' => 'nullable|exists:product_batches,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'customer_name.required_without' => 'Nama pelanggan harus diisi jika pelanggan belum dipilih.',
            'customer_phone.required_without' => 'Nomor telepon pelanggan harus diisi jika pelanggan belum dipilih.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'paid_amount.required' => 'Jumlah bayar harus diisi.',
            'paid_amount.numeric' => 'Jumlah bayar harus berupa angka.',
            'paid_amount.min' => 'Jumlah bayar tidak boleh negatif.',
            'due_date.required_if' => 'Tanggal jatuh tempo harus diisi untuk pembayaran kredit.',
            'due_date.after' => 'Tanggal jatuh tempo harus setelah hari ini.',
            'items.required' => 'Minimal satu item harus ditambahkan.',
            'items.array' => 'Format item tidak valid.',
            'items.min' => 'Minimal satu item harus ditambahkan.',
            'items.*.product_id.required' => 'Produk harus dipilih.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak valid.',
            'items.*.product_unit_id.required' => 'Unit produk harus dipilih.',
            'items.*.product_unit_id.exists' => 'Unit produk yang dipilih tidak valid.',
            'items.*.quantity.required' => 'Kuantitas harus diisi.',
            'items.*.quantity.numeric' => 'Kuantitas harus berupa angka.',
            'items.*.quantity.min' => 'Kuantitas minimal 0.01.',
            'items.*.unit_price.required' => 'Harga satuan harus diisi.',
            'items.*.unit_price.numeric' => 'Harga satuan harus berupa angka.',
            'items.*.unit_price.min' => 'Harga satuan tidak boleh negatif.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate payment amount for credit sales
            if ($this->payment_method === 'credit') {
                $totalAmount = 0;
                $items = $this->input('items', []);
                
                foreach ($items as $item) {
                    $totalAmount += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                }
                
                $discount = $this->input('discount', 0);
                $finalAmount = $totalAmount - $discount;
                $paidAmount = $this->input('paid_amount', 0);
                
                if ($paidAmount > $finalAmount) {
                    $validator->errors()->add('paid_amount', 'Jumlah bayar tidak boleh lebih dari total amount.');
                }
            }
            
            // Validate that customer exists or new customer data is provided
            if (!$this->customer_id && (!$this->customer_name || !$this->customer_phone)) {
                $validator->errors()->add('customer_id', 'Pilih pelanggan atau isi data pelanggan baru.');
            }
        });
    }
}