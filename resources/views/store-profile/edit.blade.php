@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Edit Profile Toko</h1>

        <form action="{{ route('store-profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <!-- Nama Toko -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="name" id="name" value="{{ $profile->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <!-- Alamat Toko -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                <textarea name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ $profile->address }}</textarea>
            </div>

            <!-- Nomor Telepon -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone" id="phone" value="{{ $profile->phone }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <!-- Logo Toko -->
            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700">Logo Toko</label>
                <input type="file" name="logo" id="logo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @if ($profile->logo)
                    <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Toko" class="mt-2 w-32 h-32 object-cover rounded">
                @endif
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>

@endsection