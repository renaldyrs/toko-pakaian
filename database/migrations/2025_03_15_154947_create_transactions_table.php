<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Nomor invoice
            $table->decimal('total_amount', 10, 2); // Total pembayaran
            $table->decimal('payment_amount', 10, 2); // Uang bayar
            $table->decimal('change_amount', 10, 2); // Kembalian
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null'); // Metode pembayaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};