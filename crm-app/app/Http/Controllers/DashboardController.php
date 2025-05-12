<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\Transaction;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'customerCount'    => Customer::count(),
            'invoiceCount'     => Invoice::count(),
            'proposalCount'    => Proposal::count(),
            'transactionCount' => Transaction::count(), // ✅ Added this line
        ]);
    }
}
