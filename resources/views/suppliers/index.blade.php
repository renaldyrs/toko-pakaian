
@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4">
    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Tambah Supplier</h1>

    <!-- Form Tambah Supplier -->
    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Supplier -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Supplier</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Telepon</label>
                    <input type="text" name="phone" id="phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                    <textarea name="address" id="address"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        required></textarea>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('suppliers.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Supplier -->
    <div class="mt-10">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Daftar Supplier</h1>

        <div class="bg-white rounded-lg shadow-md overflow-x-auto dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Supplier
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Telepon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $supplier->name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $supplier->email }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $supplier->phone }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $supplier->address }}</td>
                            <td class="px-6 py-4 flex space-x-2">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection