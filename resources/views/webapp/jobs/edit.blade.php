@extends('layouts.app')

@section('title', 'Edit Job')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Job</h2>

        <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $job->position) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('position') border-red-500 @enderror">
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="closing_date" class="block text-sm font-medium text-gray-700">Closing Date</label>
                    <input type="date" name="closing_date" id="closing_date" value="{{ old('closing_date', $job->closing_date ? $job->closing_date->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="1" {{ old('status', $job->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $job->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Job
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

