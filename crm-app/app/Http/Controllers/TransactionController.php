<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('invoice')->latest()->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'invoice_id' => $transaction->invoice_id,
                'customer_name' => $transaction->invoice->name ?? 'N/A', // <- FIXED LINE
                'amount' => $transaction->amount,
                'status' => $transaction->status ?? 'Pending',
                'payment_method' => $transaction->payment_method ?? 'Card Payment',
                'created_at' => $transaction->created_at->toDateTimeString(),
            ];
        });

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $invoice = \App\Models\Invoice::find($request->invoice_id);

        Transaction::create([
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'status' => $request->status,
            'customer_name' => $invoice->name, // <- STORED IN DB
            'payment_method' => 'Card Payment',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded.');
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('invoice'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $invoice = \App\Models\Invoice::find($request->invoice_id);

        $transaction->update([
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'status' => $request->status,
            'customer_name' => $invoice->name,
            'payment_method' => 'Card Payment',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }
}
