@extends('webapp.auth.layout')

@section('title', 'Admin Login')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-10 w-full max-w-md mx-auto">
    <!-- Welcome Message -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">
            Welcome Back
        </h1>
        <p class="text-sm text-gray-500">
            Sign in to your admin account
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.authenticate') }}" class="space-y-5">
        @csrf
        
        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email" 
                       required 
                       value="{{ old('email') }}"
                       class="block w-full pl-10 pr-3 py-2.5 border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 sm:text-sm transition duration-150"
                       placeholder="Enter your email">
            </div>
            @error('email')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="current-password" 
                       required 
                       class="block w-full pl-10 pr-3 py-2.5 border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 sm:text-sm transition duration-150"
                       placeholder="Enter your password">
            </div>
            @error('password')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center">
                <input id="remember" 
                       name="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-gray-700">
                    Remember me
                </label>
            </div>
            <a href="{{ route('admin.password.request') }}" 
               class="text-blue-600 hover:text-blue-500 font-medium">
                Forgot password?
            </a>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                Sign In
            </button>
        </div>
    </form>
</div>
@endsection
