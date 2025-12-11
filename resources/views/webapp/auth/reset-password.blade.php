@extends('webapp.auth.layout')

@section('title', 'Reset Password')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
    <!-- Header Section -->
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
            Reset Password
        </h2>
        <p class="text-sm text-gray-600">
            Enter your new password below
        </p>
    </div>

    <!-- Form -->
    <form class="space-y-5" method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Email Field (Disabled) -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email Address
            </label>
            <input id="email" 
                   type="email" 
                   value="{{ $email }}" 
                   disabled
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-500 bg-gray-50 cursor-not-allowed sm:text-sm">
        </div>

        <!-- New Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                New Password
            </label>
            <input id="password" 
                   name="password" 
                   type="password" 
                   autocomplete="new-password" 
                   required 
                   class="w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 transition duration-150 ease-in-out sm:text-sm"
                   placeholder="Enter your new password">
            @error('password')
                <p class="mt-1.5 text-sm text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div>
            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1.5">
                Confirm Password
            </label>
            <input id="password-confirm" 
                   name="password_confirmation" 
                   type="password" 
                   autocomplete="new-password" 
                   required 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm"
                   placeholder="Confirm your new password">
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="mr-2 -ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Reset Password</span>
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('admin.login') }}" 
               class="text-sm font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out inline-flex items-center">
                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Login
            </a>
        </div>
    </form>
</div>
@endsection
