@extends('layouts.app')

@section('content')

    <div class="container mx-auto px-4 py-8 ">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Manajemen Kategori</h1>
            </div>

            <!-- Form dan Tabel dalam Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6 dark:bg-gray-800">
                <!-- Form Kategori (Kolom Kiri) -->
                <div>
                    <div class="lg:col-span-1 bg-gray-50 p-6 rounded-lg border shadow dark:bg-gray-700 dark:border-gray-600">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                            <i class="fas fa-plus-circle mr-2 text-blue-500"></i>
                            Tambah Kategori Baru
                        </h2>

                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1 dark:text-white">Kode
                                    Kategori</label>
                                <input type="text" id="code" name="code"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan kode kategori">
                            </div>

                            <div class="mb-4">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1 dark:text-white">Nama
                                    Kategori</label>
                                <input type="text" id="name" name="name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan nama kategori">
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="reset"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:text-gray-300">
                                    Reset
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Kategori (Kolom Kanan) -->
                <div class="lg:col-span-2 shadow rounded-lg">
                    <div class="bg-white rounded-lg border overflow-hidden">
                        <div class="px-6 py-4 border-b flex justify-between items-center dark:bg-gray-700">
                            <h2 class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-list-alt mr-2 text-blue-500"></i>
                                Daftar Kategori
                            </h2>
                            <div class="relative">
                                <input type="text" placeholder="Cari kategori..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="overflow-x-auto dark:bg-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:bg-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-600">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Kategori
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kode Kategori
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700">
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $category->code }}</div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('categories.edit', $category->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                    </>
                                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700"
                                                            data-confirm-delete="true">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <hr>
                            <div class="p-4 shadow">
                                {{ $categories->links('vendor.tailwind') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection