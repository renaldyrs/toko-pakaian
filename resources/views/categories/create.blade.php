@extends('layouts.app')

@section('content')
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Tambah Kategori</h1>

        <!-- Form Tambah Kategori -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Kategori</label>
                    <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection