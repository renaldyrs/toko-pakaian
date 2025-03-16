@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Supplier</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $supplier->name }}</h5>
            <p class="card-text"><strong>Nama Kontak:</strong> {{ $supplier->contact_name }}</p>
            <p class="card-text"><strong>Email:</strong> {{ $supplier->email }}</p>
            <p class="card-text"><strong>Telepon:</strong> {{ $supplier->phone }}</p>
            <p class="card-text"><strong>Alamat:</strong> {{ $supplier->address }}</p>
        </div>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection