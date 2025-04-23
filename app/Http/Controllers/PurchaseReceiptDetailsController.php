<?php

namespace App\Http\Controllers;

use App\Models\purchase_receipt_details;
use App\Http\Requests\Storepurchase_receipt_detailsRequest;
use App\Http\Requests\Updatepurchase_receipt_detailsRequest;

class PurchaseReceiptDetailsController extends Controller
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
    public function store(Storepurchase_receipt_detailsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(purchase_receipt_details $purchase_receipt_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase_receipt_details $purchase_receipt_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepurchase_receipt_detailsRequest $request, purchase_receipt_details $purchase_receipt_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(purchase_receipt_details $purchase_receipt_details)
    {
        //
    }
}
