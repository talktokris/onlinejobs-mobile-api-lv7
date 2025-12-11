@extends('layouts.app')

@section('title', 'Employer Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Employer Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.employers.edit', $employer->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.employers.destroy', $employer->id) }}" onsubmit="return confirm('Are you sure you want to delete this employer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Employer Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Employer Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $employer->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $employer->employer_profile->company_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $employer->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $employer->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <p class="mt-1">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employer->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employer->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- All Job Ads -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">All Job Ads ({{ $jobs->count() }})</h2>
        @if($jobs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closing Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->position ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->closing_date ? $job->closing_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \App\Models\JobApplicant::where('job_id', $job->id)->where('status', 1)->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.employers.jobs.show', [$employer->id, $job->id]) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No job ads found</p>
        @endif
    </div>

    <!-- Resume Bookmarks -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resume Bookmarks ({{ $bookmarks->count() }})</h2>
        @if($bookmarks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookmarked Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookmarks as $bookmark)
                            @if($bookmark->resume_details && $bookmark->resume_details->count() > 0)
                                @foreach($bookmark->resume_details as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bookmark->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No bookmarks found</p>
        @endif
    </div>

    <!-- Applied Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Applied Users ({{ $appliedUsers->count() }})</h2>
        @if($appliedUsers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appliedUsers as $application)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->job->position ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No applied users found</p>
        @endif
    </div>
</div>
@endsection

