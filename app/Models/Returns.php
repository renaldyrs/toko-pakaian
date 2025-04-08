<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Return.php
class Returns extends Model
{
    protected $table = 'returns'; // Karena 'return' adalah keyword PHP
    
    protected $fillable = [
        'return_number',
        'transaction_id',
        'product_id',
        'quantity',
        'user_id',
        'reason',
        'total_refund',
        'status'
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
   
}