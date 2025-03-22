<?php

namespace App\Http\Controllers;

use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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
            ->latest()
            ->paginate(10); // Tambahkan paginate di sini

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax() || $request->get('_ajax')) {
            return response()->json($customers);
        }

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

    // API untuk select2
    public function search(Request $request)
    {
        $term = $request->get('term');

        $customers = Customer::where('nama', 'like', "%{$term}%")
            ->orWhere('nik', 'like', "%{$term}%")
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'text' => "$customer->nama - $customer->nik"
                ];
            });

        return response()->json(['results' => $customers]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CustomersImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Data pelanggan berhasil diimpor!');
    }
}
