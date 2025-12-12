@extends('layouts.app')

@section('title', 'Employer Details')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Employer Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.employers.edit', $employer->id) }}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.employers.destroy', $employer->id) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this employer?');" 
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Employer Information -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header Section with Logo and Key Info -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @php
                        $companyLogo = null;
                        if ($employer->employer_profile && $employer->employer_profile->company_logo) {
                            $logoPath = public_path('assets/user_images/' . $employer->id . '/' . $employer->employer_profile->company_logo);
                            if (file_exists($logoPath)) {
                                $companyLogo = asset('assets/user_images/' . $employer->id . '/' . $employer->employer_profile->company_logo);
                            }
                        }
                    @endphp
                    @if($companyLogo)
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white">
                            <img src="{{ $companyLogo }}" alt="Company Logo" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">{{ $employer->employer_profile->company_name ?? ($employer->name ?? 'N/A') }}</h2>
                        <p class="text-blue-100 text-sm">{{ $employer->name ?? 'N/A' }}</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full {{ $employer->status == 1 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $employer->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section - Two Column Layout -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column - Contact Information -->
                <div class="space-y-4">
                    <div class="flex items-center mb-3 pb-2 border-b-2 border-gray-200">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Contact Information</h3>
                    </div>
                    
                    @if($employer->phone)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Phone</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $employer->phone }}</p>
                        </div>
                    </div>
                    @endif

                    @if($employer->email)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Email</p>
                            <p class="text-sm font-semibold text-gray-900 break-words">{{ $employer->email }}</p>
                        </div>
                    </div>
                    @endif

                    @if($employer->employer_profile && $employer->employer_profile->website)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Website</p>
                            <a href="{{ $employer->employer_profile->website }}" target="_blank" class="text-sm font-semibold text-blue-600 hover:text-blue-800 break-words">
                                {{ $employer->employer_profile->website }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Location Information -->
                <div class="space-y-4">
                    <div class="flex items-center mb-3 pb-2 border-b-2 border-gray-200">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Location Information</h3>
                    </div>

                    @if($employer->employer_profile)
                        @php
                            $countryName = $employer->employer_profile->company_country_data ? $employer->employer_profile->company_country_data->name : null;
                            $cityName = $employer->employer_profile->company_city_data ? $employer->employer_profile->company_city_data->name : null;
                            $stateName = $employer->employer_profile->company_state_data ? $employer->employer_profile->company_state_data->name : null;
                        @endphp

                        @if($employer->employer_profile->company_address)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Address</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $employer->employer_profile->company_address }}</p>
                            </div>
                        </div>
                        @endif

                        @if($cityName)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">City</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $cityName }}</p>
                            </div>
                        </div>
                        @endif

                        @if($stateName)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">State</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $stateName }}</p>
                            </div>
                        </div>
                        @endif

                        @if($countryName)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Country</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $countryName }}</p>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Job Ads -->
    <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            All Job Ads ({{ $jobs->count() }})
        </h2>
        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($jobs as $job)
                    @php
                        $position = $job->post ? $job->post->name : ($job->position ?? 'N/A');
                        $companyName = $job->employer ? $job->employer->company_name : 'N/A';
                        $location = '';
                        if ($job->employer) {
                            $locationParts = [];
                            if ($job->employer->company_city_data && $job->employer->company_city_data->name) {
                                $locationParts[] = $job->employer->company_city_data->name;
                            }
                            if ($job->employer->company_country_data && $job->employer->company_country_data->name) {
                                $locationParts[] = $job->employer->company_country_data->name;
                            }
                            $location = implode(', ', $locationParts) ?: 'N/A';
                        }
                        $salary = '';
                        if ($job->salary_offer && is_numeric($job->salary_offer)) {
                            $currency = $job->salary_offer_currency ?: 'RM';
                            $period = $job->salary_offer_period ?: 'Monthly';
                            $salary = $currency . ' ' . number_format((float)$job->salary_offer) . ' / ' . $period;
                        }
                        $jobType = $job->job_vacancies_type ?? 'Full Time';
                        $postedDate = $job->created_at ? $job->created_at->diffForHumans() : 'N/A';
                        $closingDate = $job->closing_date ? $job->closing_date->format('Y-m-d') : 'N/A';
                        $applicantsCount = \App\Models\JobApplicant::where('job_id', $job->id)->where('status', 1)->count();
                        // Get first applicant's user_id for navigation to job details page
                        // If no applicant, get first job seeker who bookmarked this job, or use any job seeker
                        $firstApplicant = \App\Models\JobApplicant::where('job_id', $job->id)->where('status', 1)->first();
                        if ($firstApplicant) {
                            $jobSeekerId = $firstApplicant->user_id;
                        } else {
                            // Try to get a job seeker who bookmarked this job
                            $bookmark = \App\Models\JobBookmark::where('job_id', $job->id)->where('delete_status', 0)->first();
                            $jobSeekerId = $bookmark ? $bookmark->user_id : 1; // Fallback to 1 if no bookmarks
                        }
                    @endphp
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-orange-200 relative">
                        <!-- Status Badges -->
                        <div class="absolute top-3 right-3 flex flex-col items-end gap-1">
                            <!-- Active Status Badge -->
                            @if($job->status == 1)
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Running
                            </span>
                            @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Inactive
                            </span>
                            @endif
                            <!-- Publish Status Badge -->
                            @if($job->publish_status == 1)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Published
                            </span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                Draft
                            </span>
                            @endif
                        </div>

                        <!-- Job Image/Logo Placeholder -->
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>

                        <!-- Vacancies -->
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-gray-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-xs text-gray-600">{{ $job->total_number_of_vacancies ?? 0 }} Vacancies</span>
                        </div>

                        <!-- Job Title -->
                        <h3 class="font-bold text-gray-900 text-base mb-1">{{ $position }}</h3>

                        <!-- Company Name -->
                        <p class="text-xs text-gray-600 mb-3">{{ $companyName }}</p>

                        <!-- Job Type Badge -->
                        @if($jobType)
                        <div class="inline-flex items-center px-2 py-1 bg-yellow-100 rounded-md mb-2">
                            <svg class="w-3 h-3 text-yellow-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-xs font-medium text-yellow-800">{{ $jobType }}</span>
                        </div>
                        @endif

                        <!-- Location -->
                        @if($location)
                        <div class="flex items-center text-xs text-gray-600 mb-2">
                            <svg class="w-3 h-3 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $location }}
                        </div>
                        @endif

                        <!-- Posted Time and Expiry -->
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $postedDate }}
                            </div>
                            <div class="flex items-center text-xs text-red-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $closingDate }}
                            </div>
                        </div>

                        <!-- Salary -->
                        @if($salary)
                        <div class="mt-2 pt-2 border-t border-orange-200">
                            <p class="text-sm font-bold text-green-600">{{ $salary }}</p>
                        </div>
                        @endif

                        <!-- Applicants Count -->
                        <div class="mt-2 text-xs text-gray-600 mb-3">
                            {{ $applicantsCount }} Applicant(s)
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-3 border-t border-orange-200">
                            <a href="{{ route('admin.job-seekers.jobs.show', [$jobSeekerId, $job->id]) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                View Details
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.employers.jobs.change-publish', [$employer->id, $job->id]) }}" 
                               class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Change Publish
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No job ads found</p>
        @endif
    </div>

    <!-- Resume Bookmarks -->
    @if($bookmarks && $bookmarks->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            Resume Bookmarks ({{ $bookmarks->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($bookmarks as $bookmark)
                @if($bookmark->resume_details && $bookmark->resume_details->count() > 0)
                    @foreach($bookmark->resume_details as $user)
                        @php
                            $profile = $user->user_profile_info;
                            $userImage = null;
                            if ($profile && $profile->image) {
                                $imagePath = public_path('assets/user_images/' . $user->id . '/' . $profile->image);
                                if (file_exists($imagePath)) {
                                    $userImage = asset('assets/user_images/' . $user->id . '/' . $profile->image);
                                }
                            }
                        @endphp
                        <a href="{{ route('admin.job-seekers.show', $user->id) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-gray-200">
                            <!-- User Profile Image -->
                            <div class="flex items-start mb-3">
                                @if($userImage)
                                    <img src="{{ $userImage }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 mr-3">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center mr-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $user->name ?? 'N/A' }}</h3>
                                    <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                        Bookmarked
                                    </span>
                                </div>
                            </div>

                            <!-- User Details -->
                            @if($profile)
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                @if($profile->date_of_birth)
                                <div>
                                    <span class="font-semibold text-gray-700">Date of Birth:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->date_of_birth }}</span>
                                </div>
                                @endif
                                @if($profile->gender_data && $profile->gender_data->name)
                                <div>
                                    <span class="font-semibold text-gray-700">Gender:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->gender_data->name }}</span>
                                </div>
                                @endif
                                @if($profile->marital_status_data && $profile->marital_status_data->name)
                                <div>
                                    <span class="font-semibold text-gray-700">Marital Status:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->marital_status_data->name }}</span>
                                </div>
                                @endif
                                @if($profile->religion_data && $profile->religion_data->name)
                                <div>
                                    <span class="font-semibold text-gray-700">Religion:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->religion_data->name }}</span>
                                </div>
                                @endif
                                @if($profile->weight)
                                <div>
                                    <span class="font-semibold text-gray-700">Weight:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->weight }}</span>
                                </div>
                                @endif
                                @if($profile->height)
                                <div>
                                    <span class="font-semibold text-gray-700">Height:</span>
                                    <span class="text-gray-900 ml-1">
                                        @php
                                            $heightParts = explode('.', $profile->height);
                                            $heightDisplay = count($heightParts) === 2 ? $heightParts[0] . "'" . $heightParts[1] . '"' : $profile->height;
                                        @endphp
                                        {{ $heightDisplay }}
                                    </span>
                                </div>
                                @endif
                                @if($profile->country_data && $profile->country_data->name)
                                <div>
                                    <span class="font-semibold text-gray-700">Country:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->country_data->name }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Contact Info -->
                            <div class="mt-3 pt-3 border-t border-gray-200 space-y-1">
                                @if($profile && $profile->email)
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-700">Email:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->email }}</span>
                                </div>
                                @elseif($user->email)
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-700">Email:</span>
                                    <span class="text-gray-900 ml-1">{{ $user->email }}</span>
                                </div>
                                @endif
                                @if($profile && $profile->phone)
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-700">Phone No:</span>
                                    <span class="text-gray-900 ml-1">{{ $profile->phone }}</span>
                                </div>
                                @elseif($user->phone)
                                <div class="text-xs">
                                    <span class="font-semibold text-gray-700">Phone No:</span>
                                    <span class="text-gray-900 ml-1">{{ $user->phone }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Bookmarked Date -->
                            <div class="mt-2 text-xs text-gray-500">
                                Bookmarked: {{ $bookmark->created_at->format('M d, Y') }}
                            </div>
                        </a>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Applied Users -->
    @if($appliedUsers && $appliedUsers->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Applied Users ({{ $appliedUsers->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($appliedUsers as $application)
                @if($application->user && $application->job)
                    @php
                        $user = $application->user;
                        $profile = $user->user_profile_info;
                        $job = $application->job;
                        $position = $job->post ? $job->post->name : 'N/A';
                        $userImage = null;
                        if ($profile && $profile->image) {
                            $imagePath = public_path('assets/user_images/' . $user->id . '/' . $profile->image);
                            if (file_exists($imagePath)) {
                                $userImage = asset('assets/user_images/' . $user->id . '/' . $profile->image);
                            }
                        }
                    @endphp
                    <a href="{{ route('admin.job-seekers.show', $user->id) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-gray-200">
                        <!-- User Profile Image -->
                        <div class="flex items-start mb-3">
                            @if($userImage)
                                <img src="{{ $userImage }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 mr-3">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center mr-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $user->name ?? 'N/A' }}</h3>
                                @if($job)
                                <p class="text-xs text-blue-600 mb-1">Job: {{ $position }}</p>
                                @endif
                                @php
                                    $statusText = 'Applied';
                                    if ($application->selection_status == 1) {
                                        $statusText = 'Screening';
                                    } elseif ($application->selection_status == 2) {
                                        $statusText = 'Selected';
                                    } elseif ($application->selection_status == 3) {
                                        $statusText = 'Hired';
                                    }
                                @endphp
                                <span class="inline-block px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded">
                                    Status: {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <!-- User Details -->
                        @if($profile)
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @if($profile->date_of_birth)
                            <div>
                                <span class="font-semibold text-gray-700">Date of Birth:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->date_of_birth }}</span>
                            </div>
                            @endif
                            @if($profile->gender_data && $profile->gender_data->name)
                            <div>
                                <span class="font-semibold text-gray-700">Gender:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->gender_data->name }}</span>
                            </div>
                            @endif
                            @if($profile->marital_status_data && $profile->marital_status_data->name)
                            <div>
                                <span class="font-semibold text-gray-700">Marital Status:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->marital_status_data->name }}</span>
                            </div>
                            @endif
                            @if($profile->religion_data && $profile->religion_data->name)
                            <div>
                                <span class="font-semibold text-gray-700">Religion:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->religion_data->name }}</span>
                            </div>
                            @endif
                            @if($profile->weight)
                            <div>
                                <span class="font-semibold text-gray-700">Weight:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->weight }}</span>
                            </div>
                            @endif
                            @if($profile->height)
                            <div>
                                <span class="font-semibold text-gray-700">Height:</span>
                                <span class="text-gray-900 ml-1">
                                    @php
                                        $heightParts = explode('.', $profile->height);
                                        $heightDisplay = count($heightParts) === 2 ? $heightParts[0] . "'" . $heightParts[1] . '"' : $profile->height;
                                    @endphp
                                    {{ $heightDisplay }}
                                </span>
                            </div>
                            @endif
                            @if($profile->country_data && $profile->country_data->name)
                            <div>
                                <span class="font-semibold text-gray-700">Country:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->country_data->name }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Contact Info -->
                        <div class="mt-3 pt-3 border-t border-gray-200 space-y-1">
                            @if($profile && $profile->email)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->email }}</span>
                            </div>
                            @elseif($user->email)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span class="text-gray-900 ml-1">{{ $user->email }}</span>
                            </div>
                            @endif
                            @if($profile && $profile->phone)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Phone No:</span>
                                <span class="text-gray-900 ml-1">{{ $profile->phone }}</span>
                            </div>
                            @elseif($user->phone)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Phone No:</span>
                                <span class="text-gray-900 ml-1">{{ $user->phone }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Applied Date -->
                        <div class="mt-2 text-xs text-gray-500">
                            Applied: {{ $application->created_at->format('M d, Y') }}
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
