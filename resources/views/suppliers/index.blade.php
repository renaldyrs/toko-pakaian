@extends('layouts.app')

@section('content')

    <div class="container mx-auto p-4 w-1/2">
        <h1 class="text-3xl font-bold mb-6">Tambah Supplier</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6 mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="col-6 mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="phone" id="phone"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        required></textarea>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('suppliers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <body class="bg-gray-100">
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-6">Daftar Supplier</h1>

            <!-- Tabel Daftar Supplier -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Supplier</th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td class="px-6 py-4">{{ $supplier->name }}</td>

                                <td class="px-6 py-4">{{ $supplier->email }}</td>
                                <td class="px-6 py-4">{{ $supplier->phone }}</td>
                                <td class="px-6 py-4">{{ $supplier->address }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                        class="text-blue-500 hover:text-blue-700 mr-2">
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
    </body>
@endsection