<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category'])->paginate(5);
        $categories = Category::all();
        $suppliers = Supplier::all();
        $sizes = Size::all();
        return view('products.index', compact('products', 'categories', 'suppliers', 'sizes'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.create', compact('suppliers', 'categories', 'sizes'));
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        // Generate kode produk
        $code = Product::generateProductCode($request->category_id);

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan produk
        $product = Product::create([
            'code' => $code,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        // Simpan ukuran dan stok
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size) {
                $sizeModel = Size::firstOrCreate(['name' => $size['name']]);
                $product->sizes()->attach($sizeModel->id, ['stock' => $size['stock']]);
            }
        }

        // Generate barcode
        $product->generateBarcode();

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.edit', compact('product', 'suppliers', 'categories', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'nullable|array',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // Simpan data produk
        $product->update($data);

        // Perbarui ukuran dan stok
        if ($request->has('sizes')) {
            // Hapus semua ukuran yang terkait dengan produk
            $product->sizes()->detach();

            // Tambahkan ukuran baru atau perbarui stok
            foreach ($request->sizes as $size) {
                $sizeModel = Size::firstOrCreate(['name' => $size['name']]);
                $product->sizes()->attach($sizeModel->id, ['stock' => $size['stock']]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function generateBarcode($code)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        return response($barcode)->header('Content-Type', 'image/png');
    }
    public function findByCode(Request $request)
    {
        $code = $request->query('code'); // Ambil kode dari query parameter
        $product = Product::where('code', $code)->first(); // Cari produk berdasarkan kode

        return response()->json($product); // Kembalikan data produk dalam format JSON
    }

    public function downloadBarcode($id)
    {
        $product = Product::findOrFail($id);

        // Generate barcode
        $barcodeImage = DNS1D::getBarcodePNG($product->barcode, 'C128');

        // Set header untuk response
        $headers = [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $product->barcode . '.png"',
        ];

        // Return response dengan gambar barcode
        return response($barcodeImage, Response::HTTP_OK, $headers);
    }

    public function printBarcodes($id)
    {
        $products = Product::where('id', $id)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();
        return view('products.barcode', compact('products'));
    }


}