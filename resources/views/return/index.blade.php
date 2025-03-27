@extends('layouts.app')
@section('content')

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Retur</h1>
    
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">No. Retur</th>
                    <th class="px-4 py-2">No. Transaksi</th>
                    <th class="px-4 py-2">Produk</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Alasan</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $return)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $return->return_number }}</td>
                    <td class="px-4 py-2">{{ $return->transaction->invoice_number }}</td>
                    <td class="px-4 py-2"></td>
                    <td class="px-4 py-2">{{ $return->quantity }}</td>
                    <td class="px-4 py-2">{{ Str::limit($return->reason, 30) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($return->status == 'approved') bg-green-100 text-green-800
                            @elseif($return->status == 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $return->status }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($return->status == 'pending')
                        <div class="flex space-x-2">
                            <form action="{{ route('returns.approve', $return->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800">
                                    Setuju
                                </button>
                            </form>
                            <form action="{{ route('returns.reject', $return->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection