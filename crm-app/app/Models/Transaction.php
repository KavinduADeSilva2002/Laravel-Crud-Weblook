<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'customer_name',
        'amount',
        'status',
        'payment_method',
        'transaction_id',
    ];

    /**
     * Relationship: Transaction belongs to an invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Optional shortcut: Access the customer directly via the invoice
     */
    public function customer()
    {
        return $this->invoice ? $this->invoice->customer : null;
    }
}
