@extends('layouts.app')

@section('title', 'Job Seeker Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Job Seeker Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.job-seekers.edit', $jobSeeker->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.job-seekers.destroy', $jobSeeker->id) }}" onsubmit="return confirm('Are you sure you want to delete this job seeker?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $jobSeeker->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $jobSeeker->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $jobSeeker->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jobSeeker->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $jobSeeker->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    @if($jobSeeker->profile)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Profile Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $jobSeeker->profile->date_of_birth ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $jobSeeker->profile->gender ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Job Bookmarks -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Bookmarks ({{ $bookmarks->count() }})</h2>
        @if($bookmarks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookmarked Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookmarks as $bookmark)
                            @if($bookmark->job)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bookmark->job->position ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bookmark->job->employer->company_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bookmark->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No bookmarks found</p>
        @endif
    </div>

    <!-- Applied Jobs -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Applied Jobs ({{ $appliedJobs->count() }})</h2>
        @if($appliedJobs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appliedJobs as $application)
                            @if($application->job)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->job->position ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->job->employer->company_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No applied jobs found</p>
        @endif
    </div>
</div>
@endsection

