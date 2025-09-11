<?php

namespace App\Http\Controllers;

use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->withCount(['sales' => function ($query) {
                // Hanya hitung sales yang tidak di-soft delete
                $query->whereNull('deleted_at');
            }])
            ->withSum(['sales' => function ($query) {
                // Hanya jumlahkan sales yang tidak di-soft delete
                $query->whereNull('deleted_at');
            }], 'total_amount')
            ->when($request->get('search'), function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            })
            ->orderBy('nama', 'asc')
            ->paginate(10); // Tambahkan paginate di sini

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax() || $request->get('_ajax')) {
            return response()->json($customers);
        }

        // dd($customers);
        // die();
        // Jika bukan request AJAX, kembalikan view
        return view('customers.index', compact('customers'));
    }
    // public function index(Request $request)
    // {
    //     // Ambil parameter pencarian (jika ada)
    //     $search = $request->get('search');

    //     // Query untuk mengambil data pelanggan, jika ada pencarian, filter berdasarkan nama atau kontak
    //     $customers = Customer::query()
    //         ->when($search, function ($query, $search) {
    //             return $query->where('name', 'like', "%{$search}%")
    //                 ->orWhere('phone', 'like', "%{$search}%")
    //                 ->orWhere('email', 'like', "%{$search}%");
    //         })
    //         // Tampilkan data dengan paginasi, misalnya 10 pelanggan per halaman
    //         ->paginate(10);

    //     // Kirim data pelanggan ke view
    //     return view('customers.index', compact('customers'));
    // }


    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:customers,nik',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'provinsi_id' => 'required|string',
            'kabupaten_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'provinsi_nama' => 'required|string',
            'kabupaten_nama' => 'required|string',
            'kecamatan_nama' => 'required|string',
            'desa_nama' => 'required|string',
        ]);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:customers,nik,' . $customer->id,
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'provinsi_id' => 'required|string',
            'kabupaten_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'provinsi_nama' => 'required|string',
            'kabupaten_nama' => 'required|string',
            'kecamatan_nama' => 'required|string',
            'desa_nama' => 'required|string',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function show(Customer $customer)
    {
        $customer = Customer::with(['sales' => function ($query) {
            $query->latest();
        }])->findOrFail($customer->id);


        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);
        // dd($customer);
        // die();
        return view('customers.edit', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists()) {
            return back()->with('error', 'Cannot delete customer with sales history');
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::query()
            ->withCount('sales')
            ->withSum('sales', 'total_amount')
            ->with(['desa:id,nama', 'kecamatan:id,nama'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nik', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhereHas('desa', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('kecamatan', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        // Pastikan mengembalikan array
        return response()->json([
            'data' => $customers->toArray()
        ]);
    }
    /**
     * Import data pelanggan dari file Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Log aktivitas import
            Log::info('Memulai import data pelanggan', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'file_name' => $request->file('excel_file')->getClientOriginalName(),
                'file_size' => $request->file('excel_file')->getSize(),
            ]);

            // Proses import
            Excel::import(new CustomersImport, $request->file('excel_file'));

            // Log sukses
            Log::info('Import data pelanggan berhasil', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
            ]);

            // Hitung jumlah data yang berhasil diimpor
            $importedCount = Customer::where('created_at', '>=', now()->subMinutes(1))->count();
            
            $successMessage = "Data pelanggan berhasil diimpor!";
            if ($importedCount > 0) {
                $successMessage .= " Total {$importedCount} pelanggan telah ditambahkan.";
            }
            
            return redirect()->back()->with('success', $successMessage);
        } catch (ValidationException $e) {
            // Tangkap error validasi secara spesifik
            $failures = $e->failures();
            $errorMessages = [];
            $totalErrors = count($failures);

            // Batasi tampilan error maksimal 10 untuk menghindari tampilan yang terlalu panjang
            $maxDisplayErrors = 10;
            $displayedErrors = 0;

            foreach ($failures as $failure) {
                if ($displayedErrors >= $maxDisplayErrors) {
                    break;
                }
                
                // Format pesan error yang lebih user-friendly
                $rowNumber = $failure->row();
                $errors = $failure->errors();
                $values = $failure->values() ?? [];
                
                // Buat pesan error yang lebih deskriptif
                $errorDetails = [];
                foreach ($errors as $field => $messages) {
                    $fieldName = $this->getFieldDisplayName($field);
                    $errorDetails[] = "{$fieldName}: " . implode(', ', $messages);
                }
                
                $errorMessage = "<strong>Baris {$rowNumber}:</strong> " . implode(' | ', $errorDetails);
                if (!empty($values['nama'])) {
                    $errorMessage .= " <em>(Nama: {$values['nama']})</em>";
                }
                
                $errorMessages[] = $errorMessage;
                $displayedErrors++;

                // Log setiap error validasi
                Log::warning('Validasi gagal saat import data pelanggan', [
                    'row' => $failure->row(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values() ?? [],
                ]);
            }

            // Tambahkan informasi jika ada lebih banyak error
            if ($totalErrors > $maxDisplayErrors) {
                $remainingErrors = $totalErrors - $maxDisplayErrors;
                $errorMessages[] = "<em>... dan {$remainingErrors} error lainnya. Silakan periksa file Excel Anda.</em>";
            }

            $errorHeader = "<strong>Gagal mengimpor data ({$totalErrors} error ditemukan):</strong><br><br>";
            return redirect()->back()->with('error', $errorHeader . implode('<br><br>', $errorMessages));
        } catch (\Exception $e) {
            // Log error umum
            Log::error('Error saat import data pelanggan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
            ]);

            // Tangkap error umum lainnya
            $userFriendlyMessage = 'Terjadi kesalahan saat mengimpor data. ';
            
            // Berikan pesan yang lebih spesifik berdasarkan jenis error
            if (strpos($e->getMessage(), 'file') !== false) {
                $userFriendlyMessage .= 'Pastikan file Excel yang Anda upload valid dan tidak rusak.';
            } elseif (strpos($e->getMessage(), 'memory') !== false) {
                $userFriendlyMessage .= 'File terlalu besar. Coba upload file dengan data yang lebih sedikit.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $userFriendlyMessage .= 'Proses import memakan waktu terlalu lama. Coba dengan file yang lebih kecil.';
            } else {
                $userFriendlyMessage .= 'Silakan periksa format file dan coba lagi.';
            }
            
            return redirect()->back()->with('error', $userFriendlyMessage . '<br><br><small>Detail error: ' . $e->getMessage() . '</small>');
        }
    }
    
    /**
     * Get user-friendly field display name
     */
    private function getFieldDisplayName($field)
    {
        $fieldNames = [
            'nik' => 'NIK',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
        ];
        
        return $fieldNames[$field] ?? ucfirst($field);
    }
    
    public function downloadTemplate()
    {
        // Buat template baru menggunakan CustomersTemplateExport
        $filename = 'customer_import_template.xlsx';
        $path = storage_path('app/public/templates/');

        // Pastikan direktori ada
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Buat file template baru
        Excel::store(new \App\Exports\CustomersTemplateExport, 'public/templates/' . $filename);

        // Kembalikan file yang baru dibuat
        return response()->download(storage_path('app/public/templates/' . $filename), $filename);
    }

    /**
     * Get customer purchase history
     */
    public function getCustomerHistory(Customer $customer)
    {
        // Ambil 10 transaksi terakhir
        $history = $customer->sales()
            ->select('id', 'date', 'invoice_number', 'total_amount', 'payment_status', 'payment_method')
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        // Ambil produk yang paling sering dibeli
        $favoriteProducts = DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('product_units', 'sale_details.product_unit_id', '=', 'product_units.id')
            ->select(
                'products.id',
                'products.name',
                'product_units.selling_price as price',
                DB::raw('COUNT(sale_details.product_id) as purchase_count'),
                DB::raw('SUM(sale_details.quantity) as total_quantity')
            )
            ->where('sales.customer_id', $customer->id)
            ->groupBy('products.id', 'products.name', 'product_units.selling_price')
            ->orderBy('purchase_count', 'desc')
            ->orderBy('total_quantity', 'desc')
            ->take(6)
            ->get();

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->nama
            ],
            'history' => $history,
            'favorite_products' => $favoriteProducts
        ]);
    }
}
