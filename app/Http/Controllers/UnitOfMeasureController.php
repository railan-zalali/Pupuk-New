<?php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasure;
use App\Http\Requests\StoreUnitOfMeasureRequest;
use App\Http\Requests\UpdateUnitOfMeasureRequest;
use Illuminate\Http\Request;

class UnitOfMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'abbreviation' => 'required|string|max:10',
        'is_base_unit' => 'boolean',
    ]);

    UnitOfMeasure::create($validated);

    return redirect()->route('units.index')
        ->with('success', 'Unit of measure created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(UnitOfMeasure $unitOfMeasure)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitOfMeasure $unitOfMeasure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitOfMeasureRequest $request, UnitOfMeasure $unitOfMeasure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitOfMeasure $unitOfMeasure)
    {
        //
    }
}
