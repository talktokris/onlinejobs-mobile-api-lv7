@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Dynamic Fills Settings</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $key => $category)
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category['name'] }}</h3>
                <p class="text-sm text-gray-500 mb-4">Total Items: {{ $category['count'] }}</p>
                <a href="{{ route('admin.settings.category', $key) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Manage
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

