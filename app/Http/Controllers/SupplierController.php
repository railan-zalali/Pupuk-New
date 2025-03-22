<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('purchases')->latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'nullable|string'
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    public function show(Supplier $supplier)
    {
        $supplier = Supplier::with(['purchases' => function ($query) {
            $query->with(['purchaseDetails.product'])->latest();
        }])->findOrFail($supplier->id);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->exists()) {
            return back()->with('error', 'Cannot delete supplier that has purchase history');
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }
}
