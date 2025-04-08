
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
        <i class="fas fa-undo-alt text-blue-500 mr-2"></i> Daftar Retur
    </h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No. Retur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No. Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Alasan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($returns as $return)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-700 flex items-center">
                        <i class="fas fa-receipt text-blue-500 mr-2"></i> {{ $return->return_number }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $return->transaction->invoice_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $return->product->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $return->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($return->reason, 30) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($return->status == 'approved') bg-green-100 text-green-800
                            @elseif($return->status == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($return->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($return->status == 'pending')
                        <div class="flex space-x-2">
                            <form action="{{ route('returns.approve', $return->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center text-green-600 hover:text-green-800 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Setuju
                                </button>
                            </form>
                            <form action="{{ route('returns.reject', $return->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center text-red-600 hover:text-red-800 font-medium">
                                    <i class="fas fa-times-circle mr-1"></i> Tolak
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-500 text-sm">Tidak ada aksi</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $returns->links('vendor.tailwind') }}
    </div>
</div>
@endsection