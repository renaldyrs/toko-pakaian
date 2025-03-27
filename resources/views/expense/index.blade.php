@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-2">
    <h1 class="text-2xl font-bold mb-6">Manajemen Pengeluaran</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Form Tambah Pengeluaran -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Catat Pengeluaran Baru</h2>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="date" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="amount" step="0.01" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border rounded" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Bahan Baku">Bahan Baku</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Keterangan</label>
                    <textarea name="description" class="w-full px-3 py-2 border rounded"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>

        <!-- Ringkasan Pengeluaran -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Ringkasan</h2>
            <div class="bg-blue-50 p-4 rounded mb-4">
                <p class="text-gray-600">Total Pengeluaran</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            
            <h3 class="font-medium mb-2">Per Kategori</h3>
            <ul class="space-y-2">
                @foreach($categories as $category)
                @php
                    $total = DB::table('expenses')->where('category', $category)->sum('amount');
                @endphp
                <li class="flex justify-between">
                    <span>{{ $category }}</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Daftar Pengeluaran -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-stone-500">
                <tr>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Kategori</th>
                    <th class="px-6 py-3 text-left">Keterangan</th>
                    <th class="px-6 py-3 text-right">Jumlah</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($expenses as $expense)
                <tr>
                    <td class="px-6 py-4">{{ $expense->date }}</td>
                    <td class="px-6 py-4">{{ $expense->category }}</td>
                    <td class="px-6 py-4">{{ Str::limit($expense->description, 30) }}</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                onclick="return confirm('Hapus pengeluaran ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection