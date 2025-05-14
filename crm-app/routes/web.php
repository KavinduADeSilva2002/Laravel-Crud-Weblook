<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Mail\MyEmail;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Authenticated Routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/send-email/{invoice}', [InvoiceController::class, 'sendInvoiceEmail'])->name('invoices.sendEmail');

    // Proposals
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::get('/proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');
    Route::put('/proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
    Route::delete('/proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

// ✅ Simple test route to send mail
Route::get('/send-test-mail', function () {
    $message = "Hello from Laravel!";
    Mail::to('5f1b9e3e5be55@inbox.mailtrap.io')->send(new MyEmail($message, 1));
    return "✅ Test email sent successfully!";
});

// ✅ Debug test route with try-catch
Route::get('/test-email', function () {
    try {
        Mail::to('5f1b9e3e5be55@inbox.mailtrap.io')->send(new MyEmail('This is a test message from Weblook CRM', 1));
        return '✅ Mail sent to Mailtrap';
    } catch (\Exception $e) {
        return '❌ Failed: ' . $e->getMessage();
    }
});

// Add this route to web.php
Route::get('/test-mail', function() {
    $message = "Test Message\nThis is a test email to verify the email system is working.";
    try {
        Mail::to('test@example.com')->send(new \App\Mail\MyEmail($message, 1));
        return "Email sent successfully! Check Mailtrap inbox.";
    } catch (\Exception $e) {
        return "Error sending email: " . $e->getMessage();
    }
});

Route::get('/invoice/{id}/pay', [InvoiceController::class, 'redirectToStripe'])
    ->name('invoice.pay');
Route::get('/invoice/success', [InvoiceController::class, 'paymentSuccess'])
    ->name('invoice.success');
Route::get('/invoice/cancel', [InvoiceController::class, 'paymentCancel'])
    ->name('invoice.cancel');

require __DIR__.'/auth.php';
