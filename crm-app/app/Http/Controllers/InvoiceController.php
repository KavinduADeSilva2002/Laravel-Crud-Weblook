<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Str;
class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::with('customer')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            ...$validated,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'status' => 'draft',
        ]);

        return $invoice->load('customer');
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load('customer');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric',
            'status' => 'in:draft,sent,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($validated);

        return $invoice->load('customer');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->noContent();
    }
}

