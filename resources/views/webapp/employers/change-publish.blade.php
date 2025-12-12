@extends('layouts.app')

@section('title', 'Change Publish Status')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.employers.show', $employer->id) }}" class="mr-3 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Change Publish Status</h1>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <ul class="mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Job Information Card -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-100">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Job Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <span class="text-sm font-semibold text-gray-600">Job Title:</span>
                <p class="text-gray-900 font-medium">{{ $job->post ? $job->post->name : ($job->position ?? 'N/A') }}</p>
            </div>
            @if($employer->employer_profile)
            <div>
                <span class="text-sm font-semibold text-gray-600">Company Name:</span>
                <p class="text-gray-900 font-medium">{{ $employer->employer_profile->company_name ?? 'N/A' }}</p>
            </div>
            @endif
            @if($job->job_vacancies_type)
            <div>
                <span class="text-sm font-semibold text-gray-600">Job Type:</span>
                <p class="text-gray-900 font-medium">{{ $job->job_vacancies_type }}</p>
            </div>
            @endif
            @if($job->created_at)
            <div>
                <span class="text-sm font-semibold text-gray-600">Posted Date:</span>
                <p class="text-gray-900 font-medium">{{ $job->created_at->format('M d, Y') }}</p>
            </div>
            @endif
            @php
                $location = '';
                if ($employer->employer_profile) {
                    $locationParts = [];
                    if ($employer->employer_profile->company_city_data && $employer->employer_profile->company_city_data->name) {
                        $locationParts[] = $employer->employer_profile->company_city_data->name;
                    }
                    if ($employer->employer_profile->company_country_data && $employer->employer_profile->company_country_data->name) {
                        $locationParts[] = $employer->employer_profile->company_country_data->name;
                    }
                    $location = implode(', ', $locationParts) ?: 'N/A';
                }
            @endphp
            @if($location)
            <div>
                <span class="text-sm font-semibold text-gray-600">Location:</span>
                <p class="text-gray-900 font-medium">{{ $location }}</p>
            </div>
            @endif
            @if($job->salary_offer && is_numeric($job->salary_offer))
            <div>
                <span class="text-sm font-semibold text-gray-600">Salary:</span>
                <p class="text-gray-900 font-medium">
                    {{ $job->salary_offer_currency ?: 'RM' }} {{ number_format((float)$job->salary_offer) }}
                    @if($job->salary_offer_period)
                        / {{ $job->salary_offer_period }}
                    @endif
                </p>
            </div>
            @endif
            <div>
                <span class="text-sm font-semibold text-gray-600">Current Publish Status:</span>
                <p class="text-gray-900 font-medium">
                    @if($job->publish_status == 1)
                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            Published
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                            Draft
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Publish Status Update Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Update Publish Status
        </h2>

        <form method="POST" action="{{ route('admin.employers.jobs.update-publish', [$employer->id, $job->id]) }}" class="space-y-6">
            @csrf

            <!-- Publish Status -->
            <div>
                <label for="publish_status" class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Publish Status <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    @php
                        $publishStatusBorderClass = $errors->has('publish_status') ? 'border-red-500' : 'border-gray-200';
                    @endphp
                    <select name="publish_status" 
                            id="publish_status" 
                            required
                            class="block w-full pl-12 pr-10 py-3.5 border-2 {{ $publishStatusBorderClass }} rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 appearance-none cursor-pointer">
                        <option value="1" {{ old('publish_status', $job->publish_status) == 1 ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ old('publish_status', $job->publish_status) == 0 ? 'selected' : '' }}>Draft</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('publish_status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Published jobs are visible to job seekers, Draft jobs are hidden
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.employers.show', $employer->id) }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200 font-medium shadow-sm border border-gray-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 font-semibold shadow-lg transform hover:scale-105 active:scale-95 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Publish Status
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Enhanced input focus effects */
    #publish_status:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    /* Custom select arrow */
    #publish_status {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>
@endsection
