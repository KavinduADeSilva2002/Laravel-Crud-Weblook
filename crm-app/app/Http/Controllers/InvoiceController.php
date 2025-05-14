<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyEmail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class InvoiceController extends Controller
{
    /**
     * Show invoice form and list.
     */
    public function index()
    {
        return Inertia::render('Invoices/Index', [
            'customers' => Customer::all(),
            'invoices' => Invoice::all(),
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

        // Automatically send email after creation
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
     * Send invoice email to the customer with Stripe payment URL.
     */
    public function sendInvoiceEmail(Invoice $invoice)
    {
        $paymentUrl = $this->generateStripeCheckoutUrl($invoice);

        $messageText = "Dear {$invoice->name},\n\n"
            . "This is your invoice for the amount of \${$invoice->total_payment}, due on {$invoice->due_date}.\n\n"
            . "Please use the button below to make your payment securely via Stripe.";

        Mail::to($invoice->email)->send(new MyEmail($messageText, $paymentUrl));
    }

    /**
     * Generate a Stripe Checkout session and return its URL.
     */
    protected function generateStripeCheckoutUrl(Invoice $invoice)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Invoice #' . ($invoice->id),
                        ],
                        'unit_amount' => $invoice->total_payment * 100, // amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('invoices.index') . '?payment=success',
                'cancel_url' => route('invoices.index') . '?payment=cancelled',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                ],
            ]);

            return $session->url;
        } catch (\Exception $e) {
            // fallback link or error page
            return url('/invoices') . '?payment=error';
        }
    }
}
