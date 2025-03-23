@extends('layouts.app')

@section('content')

<div class="container mx-auto p-4 w-1/2">
        <h1 class="text-3xl font-bold mb-6">Tambah Kategori</h1>

        <!-- Form Tambah Kategori -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Kategori</label>
                    <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2" required>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p2" required>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2 p-2">
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
        <h1 class="text-3xl font-bold mb-6">Daftar Kategori</h1>

        <!-- Tabel Daftar Kategori -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($categories as $category)
                    <tr>
                        <td class="px-6 py-4">{{ $category->code }}</td>
                        <td class="px-6 py-4">{{ $category->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700 mr-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" data-confirm-delete="true">
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