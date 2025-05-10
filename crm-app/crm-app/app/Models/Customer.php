<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'full_name', 'email'];

    protected static function booted()
    {
        static::creating(function ($customer) {
            $customer->uuid = Str::uuid();
        });
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
