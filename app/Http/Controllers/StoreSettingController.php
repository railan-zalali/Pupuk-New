<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = StoreSetting::first() ?? new StoreSetting();
        return view('settings.index', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $settings = StoreSetting::first() ?? new StoreSetting();
        $settings->store_name = $request->store_name;
        $settings->store_address = $request->store_address;
        $settings->store_phone = $request->store_phone;
        $settings->store_email = $request->store_email;
        
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo_path && Storage::exists('public/' . $settings->logo_path)) {
                Storage::delete('public/' . $settings->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $logoPath;
        }
        
        $settings->save();
        
        return redirect()->route('settings.index')->with('success', 'Pengaturan toko berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Redirect to store method as we're using a single record
        return $this->store($request);
    }
}
