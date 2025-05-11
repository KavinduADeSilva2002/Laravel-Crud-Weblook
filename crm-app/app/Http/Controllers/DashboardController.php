<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $customerCount = Customer::count();

        return Inertia::render('Dashboard', [
            'customerCount' => $customerCount,
        ]);
    }
}
