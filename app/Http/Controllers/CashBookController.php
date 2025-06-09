<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use Illuminate\Http\Request;

class CashBookController extends Controller
{
    public function index()
    {
        $transactions = CashBook::orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('cash-book.index', compact('transactions'));
    }

    public function create()
    {
        return view('cash-book.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
            'reference_number' => 'nullable|string',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0'
        ]);

        $lastBalance = CashBook::latest()->first()?->balance ?? 0;

        $transaction = new CashBook();
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->reference_number = $request->reference_number;

        if ($request->type === 'debit') {
            $transaction->debit = $request->amount;
            $transaction->credit = 0;
            $transaction->balance = $lastBalance + $request->amount;
        } else {
            $transaction->debit = 0;
            $transaction->credit = $request->amount;
            $transaction->balance = $lastBalance - $request->amount;
        }

        $transaction->save();

        return redirect()->route('cash-book.index')
            ->with('success', 'Transaksi berhasil dicatat');
    }
}
