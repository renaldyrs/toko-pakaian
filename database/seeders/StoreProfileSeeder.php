<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoreProfile;

class StoreProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        StoreProfile::create([
            'name' => 'Nama Toko Anda',
            'address' => 'Alamat Toko Anda',
            'phone' => '081234567890',
            'logo' => null, // Path logo toko (jika ada)
        ]);
    }
}
