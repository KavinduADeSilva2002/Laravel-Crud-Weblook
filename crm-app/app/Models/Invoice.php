<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'total_payment',
        'due_date',
        'status',
    ];

    protected $attributes = [
        'status' => 'Pending'
    ];

    /**
     * Relationship: Invoice belongs to a Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship: Invoice has one Transaction
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
