@extends('layouts.login')

@section('content')
<body class="bg-gray-100 dark:bg-gray-900  flex justify-center items-center">
    
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md ">
            <div class="text-center mb-6 flex flex-col items-center">

                <div class="mb-2">
                    @if (DB::table('store_profiles')->first()->logo)
                        <img src="{{ asset('storage/' . DB::table('store_profiles')->first()->logo) }}"
                            alt="Logo Toko" class="w-23 h-23 rounded-full">
                    @else
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Belum ada logo</p>
                    
                    @endif
                    

                </div>
                <h1 class="text-2xl font-bold text-gray-800 ">{{ DB::table('store_profiles')->first()->name }}</h1>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required
                        autocomplete="email" autofocus placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                        placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @if (Route::has('password.request'))
                        <a class="btn btn-link text-sm text-gray-600 hover:text-gray-900 text-right"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ __('Login') }}
                </button>


                <div class="mt-6 text-center">
                    <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}"
                            class="text-blue-600 hover:underline">Register
                        </a></p>
                </div>

            </form>
        </div>
        
    </body>
@endsection