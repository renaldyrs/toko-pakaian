@extends('layouts.app')
@section('content')

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Form Retur Produk</h1>
    
    <div class="bg-white rounded shadow p-6">
        <form action="{{ route('returns.store') }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            <div class="mb-4">
                <label class="block text-gray-700">Produk</label>
                <select name="product_id" class="w-full border rounded p-2" required>
                    <option value="">Pilih Produk</option>
                    @foreach($transaction->details as $detail)
                    <option value="{{ $detail->product_id }}">
                        {{ $detail->product->name }} (Beli: {{ $detail->quantity }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Jumlah Retur</label>
                <input type="number" name="quantity" class="w-full border rounded p-2" required min="1">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Alasan Retur</label>
                <textarea name="reason" rows="3" class="w-full border rounded p-2" required></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Ajukan Retur
                </button>
            </div>
        </form>
    </div>
</div>

@endsection