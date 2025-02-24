@extends('layouts.authlayout')

@section('content')
<div class="container mx-auto mt-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-md">
        <div class="px-6 py-4">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Log in to SocialConnect</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" required value="{{ old('email') }}">
                    @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" required>
                    @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                @if (session('message'))
                <div class="alert alert-warning">
                    {{ session('message') }}
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Log In
                    </button>
                    <a href="{{ route('register') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        New to SocialConnect? Sign up
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
