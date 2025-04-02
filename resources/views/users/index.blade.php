@extends('layouts.app')

@section('content')

    <div class="container mx-auto">
        <div class=" grid grid-flow-col grid-rows-3 gap-4 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden row-span-2 dark:bg-gray-800">
                <h1 class="text-3xl font-bold mb-2 px-4 py-4 justify-center dark:text-white">Tambah User</h1>

                <!-- Form Tambah User -->
                <form action="{{ route('users.store') }}" method="POST" class="bg-white pt-2 p-6 rounded-lg shadow-md dark:bg-gray-800">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Nama</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div class="col-6 mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                            <input type="email" name="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div class="col-6 mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-white">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-white">Role</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>

            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden col-span-2 row-span-2  dark:bg-gray-800">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 dark:text-white">{{ $user->name }}</td>
                                <td class="px-6 py-4 dark:text-white">{{ $user->email }}</td>
                                <td class="px-6 py-4 dark:text-white">{{ $user->role }}</td>
                                <td class="px-6 py-4 dark:text-white">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="text-blue-500 hover:text-blue-700 mr-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
                <hr>
<div class="p-2">
{{ $users->links() }}
</div>
                
            </div>

        </div>

    </div>


    <div class="container mx-auto px-4 py-8 ">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Manajemen User</h1>
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
                                <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1 dark:text-white">Nama </label>
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
                                            Nama
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Role
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">if()<span >{{ $user->role }} </span></div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href=""
                                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                    </>
                                                    <form action=""
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
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

