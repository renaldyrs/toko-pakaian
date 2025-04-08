<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = [
            ['name' => 'S', 'description' => 'Small'],
            ['name' => 'M', 'description' => 'Medium'],
            ['name' => 'L', 'description' => 'Large'],
            ['name' => 'XL', 'description' => 'Extra Large'],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}