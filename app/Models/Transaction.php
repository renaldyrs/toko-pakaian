<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'total_amount',
        'payment_amount',
        'change_amount',
        'payment_method_id',
    ];

    // Relasi ke PaymentMethod
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Relasi ke TransactionDetail
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}