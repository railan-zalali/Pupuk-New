<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:products,code',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|gte:purchase_price',
            'min_stock' => 'required|integer|min:0',
            'is_perishable' => 'boolean',
            'shelf_life_days' => 'required_if:is_perishable,true|nullable|integer|min:1',
            
            // Product units validation
            'units' => 'required|array|min:1',
            'units.*.name' => 'required|string|max:50',
            'units.*.conversion_factor' => 'required|numeric|min:0.01',
            'units.*.barcode' => 'nullable|string|max:100|unique:product_units,barcode',
            'units.*.is_base_unit' => 'boolean',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'name.required' => 'Nama produk harus diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'code.required' => 'Kode produk harus diisi.',
            'code.unique' => 'Kode produk sudah digunakan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'purchase_price.required' => 'Harga beli harus diisi.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa angka.',
            'selling_price.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'min_stock.required' => 'Stok minimum harus diisi.',
            'min_stock.integer' => 'Stok minimum harus berupa angka bulat.',
            'min_stock.min' => 'Stok minimum tidak boleh negatif.',
            'shelf_life_days.required_if' => 'Masa simpan harus diisi untuk produk yang mudah rusak.',
            'shelf_life_days.integer' => 'Masa simpan harus berupa angka bulat.',
            'shelf_life_days.min' => 'Masa simpan minimal 1 hari.',
            'units.required' => 'Minimal satu unit produk harus ditambahkan.',
            'units.array' => 'Format unit produk tidak valid.',
            'units.min' => 'Minimal satu unit produk harus ditambahkan.',
            'units.*.name.required' => 'Nama unit harus diisi.',
            'units.*.name.max' => 'Nama unit maksimal 50 karakter.',
            'units.*.conversion_factor.required' => 'Faktor konversi harus diisi.',
            'units.*.conversion_factor.numeric' => 'Faktor konversi harus berupa angka.',
            'units.*.conversion_factor.min' => 'Faktor konversi minimal 0.01.',
            'units.*.barcode.unique' => 'Barcode sudah digunakan.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Ensure at least one base unit exists
            $units = $this->input('units', []);
            $hasBaseUnit = false;
            
            foreach ($units as $unit) {
                if (isset($unit['is_base_unit']) && $unit['is_base_unit']) {
                    $hasBaseUnit = true;
                    break;
                }
            }
            
            if (!$hasBaseUnit) {
                $validator->errors()->add('units', 'Minimal satu unit harus dijadikan unit dasar.');
            }
        });
    }
}
