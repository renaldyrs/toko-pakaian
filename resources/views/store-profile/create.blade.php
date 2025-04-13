
{{-- filepath: d:\toko-pakaian\resources\views\store-profile\create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Tambah Profil Toko</h1>
        <form action="{{ route('store-profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Toko -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Toko</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat Toko -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Toko</label>
                <textarea name="address" id="address" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    required>{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    required>
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo Toko -->
            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Toko</label>
                <input type="file" name="logo" id="logo"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('logo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <a href="{{ route('store-profile.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-4 py-2 rounded-md shadow-md mr-2">
                    Batal
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow-md">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection