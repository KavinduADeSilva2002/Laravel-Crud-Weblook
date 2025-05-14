<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_id',
        'customer_name',
        'amount',
        'status',
        'payment_method',
        'transaction_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
