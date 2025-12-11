@extends('layouts.app')

@section('title', 'Job Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Job Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" onsubmit="return confirm('Are you sure you want to delete this job?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Job Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Position</label>
                <p class="mt-1 text-sm text-gray-900">{{ $job->position ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company</label>
                <p class="mt-1 text-sm text-gray-900">{{ $job->employer->company_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Closing Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $job->closing_date ? $job->closing_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Salary</label>
                <p class="mt-1 text-sm text-gray-900">{{ $job->salary_offer ?? 'N/A' }}</p>
            </div>
        </div>

        @if($job->jobPointsRequirements && $job->jobPointsRequirements->count() > 0)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($job->jobPointsRequirements as $requirement)
                        <li class="text-sm text-gray-900">{{ $requirement->point ?? '' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($job->jobPointsDescriptions && $job->jobPointsDescriptions->count() > 0)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descriptions</label>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($job->jobPointsDescriptions as $description)
                        <li class="text-sm text-gray-900">{{ $description->point ?? '' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6">
            @if($employer)
                <a href="{{ route('admin.employers.show', $employer->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    View Employer Profile
                </a>
            @endif
        </div>
    </div>

    <!-- All Applicants -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">All Applicants ({{ $applicants->count() }})</h2>
        @if($applicants->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicant Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applicants as $applicant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $applicant->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $applicant->user->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $applicant->user->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $applicant->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.jobs.applicants.show', [$job->id, $applicant->user_id]) }}" class="text-blue-600 hover:text-blue-900">View Details & Messages</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No applicants found</p>
        @endif
    </div>
</div>
@endsection

