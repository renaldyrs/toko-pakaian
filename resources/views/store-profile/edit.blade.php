
@extends('layouts.app')

@section('content')
<div class="container flex justify-center mt-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Perbarui Informasi Toko</h1>
</div>

<div class="container mx-auto p-4 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg dark:bg-gray-800">
        <form action="{{ route('store-profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Logo Toko -->
            <div class="flex flex-col items-center mb-6">
            @if ($profile->logo)
                    <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Toko"
                        class="mt-4 mb-2 w-32 h-32 object-cover rounded-md shadow-md">
                @else
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Belum ada logo</p>
                @endif
                <input type="file" name="logo" id="logo" 
                    class="text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2">
                <p class="text-sm text-gray-500 mt-2">Unggah logo baru jika ingin mengganti.</p>
            </div>

            <!-- Nama Toko -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Nama Toko</h2>
                <input type="text" name="name" id="name" value="{{ old('name', $profile->name) }}" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>
            </div>

            <!-- Alamat Toko -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Alamat Toko</h2>
                <textarea name="address" id="address" rows="3" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>{{ old('address', $profile->address) }}</textarea>
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 dark:text-gray-400">Nomor Telepon</h2>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" 
                    class="mt-2 text-gray-800 dark:text-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full p-2" 
                    required>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md shadow-md transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection