@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Profil</h1>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}"
                    required>
            </div>
            <div class="form-group">
                <label for="avatar">Foto Profil</label>
                <input type="file" name="avatar" id="avatar" class="form-control-file">
                @if (auth()->user()->avatar)
                    <small class="form-text text-muted">Foto saat ini: <a href="{{ Storage::url(auth()->user()->avatar) }}"
                            target="_blank">Lihat</a></small>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>

        <hr class="my-4">

        <h3 class="mb-3">Ubah Password</h3>
        <form action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Ubah Password
            </button>
        </form>
    </div>
@endsection