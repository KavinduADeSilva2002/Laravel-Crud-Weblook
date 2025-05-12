<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(): Response
    {
        $transactions = Transaction::with('customer')->latest()->get();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }
}
