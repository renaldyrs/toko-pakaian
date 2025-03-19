@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Profil Saya</h1>
    @auth
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <!-- Foto Profil -->
                        <img src="{{ $user->avatar ? Storage::url($user->avatar) : asset('images/default-avatar.png') }}" 
                             alt="Avatar" 
                             class="rounded-circle mb-3" 
                             style="width: 150px; height: 150px;">
                    </div>
                    <div class="col-md-8">
                        <!-- Informasi Profil -->
                        <h3>{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        <p class="text-muted">Bergabung pada: {{ $user->created_at->format('d M Y') }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>Silakan login untuk melihat profil.</p>
    @endauth
</div>
@endsection