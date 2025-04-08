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

    public function addSizeToProduct($productId, $sizeName, $stock)
{
    // Cari produk berdasarkan ID
    $product = Product::findOrFail($productId);

    // Cari atau buat ukuran berdasarkan nama
    $size = Size::firstOrCreate(['name' => $sizeName]);

    // Periksa apakah ukuran sudah ada di produk
    if ($product->sizes()->where('size_id', $size->id)->exists()) {
        return "Ukuran {$sizeName} sudah ada pada produk.";
    }

    // Tambahkan ukuran ke produk dengan stok
    $product->sizes()->attach($size->id, ['stock' => $stock]);

    return "Ukuran {$sizeName} berhasil ditambahkan ke produk dengan stok {$stock}.";
}

public function updateSizeStock($productId, $sizeName, $newStock)
{
    // Cari produk berdasarkan ID
    $product = Product::findOrFail($productId);

    // Cari ukuran berdasarkan nama
    $size = Size::where('name', $sizeName)->first();

    if (!$size) {
        return "Ukuran {$sizeName} tidak ditemukan.";
    }

    // Periksa apakah ukuran ada di produk
    if (!$product->sizes()->where('size_id', $size->id)->exists()) {
        return "Ukuran {$sizeName} tidak terkait dengan produk ini.";
    }

    // Perbarui stok ukuran pada produk
    $product->sizes()->updateExistingPivot($size->id, ['stock' => $newStock]);

    return "Stok ukuran {$sizeName} berhasil diperbarui menjadi {$newStock}.";
}

public function removeSizeFromProduct($productId, $sizeName)
{
    // Cari produk berdasarkan ID
    $product = Product::findOrFail($productId);

    // Cari ukuran berdasarkan nama
    $size = Size::where('name', $sizeName)->first();

    if (!$size) {
        return "Ukuran {$sizeName} tidak ditemukan.";
    }

    // Periksa apakah ukuran ada di produk
    if (!$product->sizes()->where('size_id', $size->id)->exists()) {
        return "Ukuran {$sizeName} tidak terkait dengan produk ini.";
    }

    // Hapus ukuran dari produk
    $product->sizes()->detach($size->id);

    return "Ukuran {$sizeName} berhasil dihapus dari produk.";
}

public function getProductSizes($productId)
{
    // Cari produk berdasarkan ID
    $product = Product::with('sizes')->findOrFail($productId);

    // Ambil ukuran dan stok
    $sizes = $product->sizes->map(function ($size) {
        return [
            'name' => $size->name,
            'stock' => $size->pivot->stock,
        ];
    });

    return $sizes;
}



    // Method untuk generate barcode
    public function generateBarcode()
    {
        $this->barcode = $this->code; // Gunakan kode produk sebagai barcode
        $this->save();
    }

    // Relasi ke Size (Many-to-Many)
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
            ->withPivot('stock')
            ->withTimestamps();
    }
}