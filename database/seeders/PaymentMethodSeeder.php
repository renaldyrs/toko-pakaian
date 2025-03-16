<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            ['name' => 'Tunai', 'description' => 'Pembayaran secara tunai'],
            ['name' => 'Transfer Bank', 'description' => 'Pembayaran melalui transfer bank'],
            ['name' => 'QRIS', 'description' => 'Pembayaran menggunakan QRIS'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
