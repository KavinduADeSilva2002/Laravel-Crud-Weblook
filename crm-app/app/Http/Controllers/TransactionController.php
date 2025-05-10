<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with(['customer', 'invoice'])->latest()->get();
    }

    public function show(Transaction $transaction)
    {
        return $transaction->load(['customer', 'invoice']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
            'status' => 'required|in:pending,completed,failed',
        ]);

        return Transaction::create($validated)->load(['customer', 'invoice']);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $transaction->update($validated);
        return $transaction->load(['customer', 'invoice']);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->noContent();
    }
}