<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return Inertia::render('Invoices/Index', [
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'total_payment' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice created.');
    }
}
