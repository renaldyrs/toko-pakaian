<?php
namespace App\Http\Controllers;

use App\Models\StoreProfile;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    // Menampilkan form edit profile toko
    public function edit()
    {
        $profile = StoreProfile::first();
        return view('store-profile.edit', compact('profile'));
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

        return redirect()->route('store-profile.edit')->with('success', 'Profile toko berhasil diperbarui.');
    }
}