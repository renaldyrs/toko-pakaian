@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Supplier</h1>
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Supplier</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Telepon</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $supplier->phone }}" required>
        </div>
        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea name="address" id="address" class="form-control" rows="3" required>{{ $supplier->address }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </form>
</div>
@endsection