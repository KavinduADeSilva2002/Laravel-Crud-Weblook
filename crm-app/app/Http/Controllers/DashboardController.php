<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Inertia\Inertia;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
{
    $customerCount = Customer::count();
    $invoiceCount = Invoice::count(); // ✅ NEW

    return Inertia::render('Dashboard', [
        'customerCount' => $customerCount,
        'invoiceCount' => $invoiceCount, // ✅ NEW
    ]);
}
}
