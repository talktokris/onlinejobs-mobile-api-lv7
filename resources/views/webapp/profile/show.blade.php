@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">My Profile</h2>
            <a href="{{ route('admin.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit Profile
            </a>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 text-sm text-gray-900">Administrator</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Member Since</label>
                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

