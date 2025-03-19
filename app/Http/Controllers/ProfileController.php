<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    // Menampilkan profil user
    public function show()
    {
        $user = auth()->user(); // Ambil data user yang sedang login

        // Pastikan user tidak null
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        return view('profile.show', compact('user'));
    }

    // Menampilkan form edit profil
    public function edit()
    {
        return view('profile.edit');
    }

    // Mengupdate profil user
 

    // Mengupdate profil user
    public function update(Request $request)
    {
        $user = Auth::user();
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Opsional: upload foto profil
        ]);
        // Data yang akan diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        // Upload foto profil jika ada
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if (Auth::user()->avatar) {
                Storage::delete('public/' . Auth::user()->avatar);
            }
            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            Auth::user()->update(['avatar' => $path]);
        }
        // Update data user
        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }


    // Mengupdate password user
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak valid.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui.');
    }
}