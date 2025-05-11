<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Inertia\Inertia;
use App\Models\Invoice;
use App\Models\Proposal;


class DashboardController extends Controller
{
    public function index()
{
    $customerCount = Customer::count();
    $invoiceCount = Invoice::count(); 
    $proposalCount = Proposal::count(); 

    return Inertia::render('Dashboard', [
        'customerCount' => $customerCount,
        'invoiceCount' => $invoiceCount, 
        'proposalCount' => $proposalCount
    ]);
}
}
