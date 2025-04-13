<?php
namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index(){
        // Mengambil data profile toko pertama
        // Jika tidak ada, redirect ke halaman edit
        $storeProfile = StoreProfile::first();
        if (!$storeProfile) {
            return redirect()->route('store-profile.create')->with('error', 'Profile toko belum ada. Silakan buat profile toko terlebih dahulu.');
        }
        $profile = StoreProfile::first();
        return view('store-profile.index', compact('profile'));
    }
    public function create(){
        // Menampilkan form untuk membuat profile toko
        return view('store-profile.create');
    }
    // Menampilkan form edit profile toko
    public function edit()
    {
        $profile = StoreProfile::first();
        return view('store-profile.edit', compact('profile'));
    }

    public function store(request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek apakah profile toko sudah ada
        $profile = StoreProfile::first();
        if ($profile) {
            return redirect()->route('store-profile.edit')->with('error', 'Profile toko sudah ada.');
        }
        // Jika tidak ada, buat profile baru
        $profile = new StoreProfile();
        $profile->name = $request->name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store-profile', 'public');
            $profile->logo = $logoPath;
        } else {
            $profile->logo = 'default-logo.png'; // Ganti dengan logo default jika ada
        }
        $profile->save();
        return redirect()->route('store-profile.index')->with('success', 'Profile toko berhasil dibuat.');
       
    }

    // Mengupdate profile toko
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = StoreProfile::first();

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('store-profile', 'public');
            $profile->logo = $logoPath;
        }

        $profile->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('store-profile.index')->with('success', 'Profile toko berhasil diperbarui.');
    }
}