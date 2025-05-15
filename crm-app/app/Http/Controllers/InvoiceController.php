<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\MyEmail;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class InvoiceController extends Controller
{
    /**
     * Show invoice form and list.
     */
    public function index()
    {
        return Inertia::render('Invoices/Index', [
            'customers' => Customer::select('id', 'full_name', 'email')->get(),
            'invoices' => Invoice::with('customer:id,full_name')->get(),
        ]);
    }

    /**
     * Store a new invoice and send email.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'total_payment' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        $invoice = Invoice::create($validated);

        // Automatically send invoice email
        $this->sendInvoiceEmail($invoice);

        return redirect()->route('invoices.index')->with('success', 'Invoice created and email sent successfully.');
    }

    /**
     * Update an existing invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'total_payment' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Delete an invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Send invoice email to the customer with invoice ID link.
     */
    public function sendInvoiceEmail(Invoice $invoice)
    {
        try {
            $messageText = "Dear {$invoice->name},\n\n"
                . "This is your invoice for \${$invoice->total_payment}, due on {$invoice->due_date}.\n\n"
                . "Click below to pay securely via Stripe.";

            Mail::to($invoice->email)->send(new MyEmail($messageText, $invoice->id));
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to Stripe checkout manually via link.
     */
    public function redirectToStripe($id)
    {
        $invoice = Invoice::findOrFail($id);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Invoice #' . $invoice->id,
                            'description' => "Payment for invoice sent to {$invoice->email}",
                        ],
                        'unit_amount' => (int)($invoice->total_payment * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('invoice.success') . '?invoice_id=' . $invoice->id,
                'cancel_url' => route('invoice.cancel'),
                'customer_email' => $invoice->email,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    /**
     * Handle Stripe payment success and save transaction.
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $invoiceId = $request->get('invoice_id');
            $invoice = Invoice::findOrFail($invoiceId);

            // Update invoice status
            $invoice->status = 'Paid';
            $invoice->save();

            // Create transaction record
            DB::table('transactions')->insert([
                'invoice_id' => $invoiceId,
                'amount' => $invoice->total_payment,
                'status' => 'Success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return view('payment-success', ['invoice' => $invoice]);
        } catch (\Exception $e) {
            Log::error('Payment success handling error: ' . $e->getMessage());
            return redirect()->route('invoices.index')->with('error', 'Payment verification failed.');
        }
    }

    public function paymentCancel()
    {
        return redirect()->route('invoices.index')->with('error', 'Payment was cancelled.');
    }
}