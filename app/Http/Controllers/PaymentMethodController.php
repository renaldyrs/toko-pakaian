<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan semua metode pembayaran
        $paymentMethods = PaymentMethod::all();
        return view('payment.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk menambah metode pembayaran baru
        return view('payment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Simpan metode pembayaran baru
        $paymentMethod = new PaymentMethod();
        $paymentMethod->name = $request->name;
        $paymentMethod->description = $request->description;
        $paymentMethod->save();

        // Redirect ke halaman metode pembayaran dengan pesan sukses
        return redirect()->route('payment.index')->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Menampilkan detail metode pembayaran tertentu
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('payment.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Menampilkan form untuk mengedit metode pembayaran
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('payment.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Update metode pembayaran
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->name = $request->name;
        $paymentMethod->description = $request->description;
        $paymentMethod->save();

        // Redirect ke halaman metode pembayaran dengan pesan sukses
        return redirect()->route('payment.index')->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus metode pembayaran
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        // Redirect ke halaman metode pembayaran dengan pesan sukses
        return redirect()->route('payment.index')->with('success', 'Payment method deleted successfully.');
    }
}