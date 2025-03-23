<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama toko
            $table->text('address')->nullable(); // Alamat toko
            $table->string('phone')->nullable(); // Nomor telepon toko
            $table->string('logo')->nullable(); // Logo toko
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_profiles');
    }
};
