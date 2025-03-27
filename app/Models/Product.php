<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'stock',
        'description',
        'image',
        'category_id',
        'barcode', // Tambahkan barcode ke fillable
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Method untuk generate kode produk
    public static function generateProductCode($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            throw new \Exception("Kategori tidak ditemukan.");
        }

        // Hitung jumlah produk dalam kategori ini
        $productCount = Product::where('category_id', $categoryId)->count();

        // Format kode produk: KODEKATEGORI-NOMORURUT
        $sequentialNumber = str_pad($productCount + 1, 3, '0', STR_PAD_LEFT); // Format 001, 002, dst.
        return $category->code . $sequentialNumber;
    }

    // Method untuk generate barcode
    public function generateBarcode()
    {
        $this->barcode = $this->code; // Gunakan kode produk sebagai barcode
        $this->save();
    }

    
}