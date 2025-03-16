<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'supplier_id',
        'category_id',
        'code', // Tambahkan code ke fillable
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Method untuk generate kode produk
    public static function generateProductCode($categoryId)
    {
        // Ambil kode kategori
        $category = Category::find($categoryId);
        if (!$category) {
            throw new \Exception("Kategori tidak ditemukan.");
        }

        // Hitung jumlah produk dalam kategori ini
        $productCount = Product::where('category_id', $categoryId)->count();

        // Format kode produk: KODEKATEGORI-NOMORURUT
        $sequentialNumber = str_pad($productCount + 1, 3, '0', STR_PAD_LEFT); // Format 001, 002, dst.
        return $category->code. $sequentialNumber;
    }
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}