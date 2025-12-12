@extends('layouts.app')

@section('title', 'Job Seeker Details')

@section('content')
<style>
    .border-l-3 {
        border-left-width: 3px;
    }
</style>
<div class="space-y-3">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-2xl font-bold text-gray-900">Job Seeker Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.job-seekers.edit', $jobSeeker->id) }}" 
               class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                Edit
            </a>
            <form method="POST" action="{{ route('admin.job-seekers.destroy', $jobSeeker->id) }}" 
                  onsubmit="return confirm('Are you sure you want to delete this job seeker?');" 
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Layout: 2 columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- User Information -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                User Information
            </h2>
            <div class="flex items-start gap-4 mb-3">
                @php
                    $profileImage = null;
                    if($jobSeeker->user_profile_info && $jobSeeker->user_profile_info->image) {
                        $profileImage = asset('assets/user_images/' . $jobSeeker->id . '/' . $jobSeeker->user_profile_info->image);
                    }
                @endphp
                @if($profileImage && file_exists(public_path('assets/user_images/' . $jobSeeker->id . '/' . $jobSeeker->user_profile_info->image)))
                    <img src="{{ $profileImage }}" alt="Profile" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <div class="text-sm font-semibold text-gray-900 mb-1">{{ $jobSeeker->name ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-600 mb-1">Email: {{ $jobSeeker->email ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-600">{{ $jobSeeker->phone ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">Status:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jobSeeker->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $jobSeeker->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        @if($jobSeeker->user_profile_info)
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Personal Information
            </h2>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">Date of Birth:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->date_of_birth ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">Gender:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->gender_data->name ?? ($jobSeeker->user_profile_info->gender ?? 'N/A') }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-24">Marital Status:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->marital_status_data->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">Religion:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->religion_data->name ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">Height:</span>
                    <span class="text-sm text-gray-900">
                        @if($jobSeeker->user_profile_info->height)
                            @php
                                $heightParts = explode('.', $jobSeeker->user_profile_info->height);
                                $heightDisplay = count($heightParts) === 2 ? $heightParts[0] . "'" . $heightParts[1] . '"' : $jobSeeker->user_profile_info->height;
                            @endphp
                            {{ $heightDisplay }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">Weight:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->weight ? $jobSeeker->user_profile_info->weight . ' KG' : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Contact Information
            </h2>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">Email:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->email ?? $jobSeeker->email ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">Phone No:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->phone ?? $jobSeeker->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">Country:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->country_data->name ?? ($jobSeeker->user_profile_info->user_country->name ?? 'N/A') }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">Address:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->address ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">City / Town:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->city_data->name ?? ($jobSeeker->user_profile_info->city ?? 'N/A') }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-20">District:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->district ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-16">State:</span>
                    <span class="text-sm text-gray-900">{{ $jobSeeker->user_profile_info->state_data->name ?? ($jobSeeker->user_profile_info->state ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
        @endif

        @php
            $experiences = $jobSeeker->professional_experiences ? $jobSeeker->professional_experiences->filter(function($exp) { return !isset($exp->delete_status) || $exp->delete_status == 0; }) : collect();
        @endphp
        @if($experiences->count() > 0)
        <!-- Work Experience -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Work Experience ({{ $experiences->count() }})
            </h2>
            <div class="space-y-2">
                @foreach($experiences as $experience)
                <div class="border-l-3 border-blue-500 pl-3 py-2 bg-gray-50 rounded-r">
                    <div class="flex flex-wrap items-start gap-2">
                        <span class="font-semibold text-gray-900 text-sm">{{ $experience->designation ?? 'N/A' }}</span>
                        <span class="text-gray-500 text-sm">at</span>
                        <span class="text-gray-700 text-sm">{{ $experience->company ?? 'N/A' }}</span>
                        @if($experience->country_name && $experience->country_name->name)
                            <span class="text-gray-500 text-sm">({{ $experience->country_name->name }})</span>
                        @endif
                        <span class="text-gray-500 text-sm">•</span>
                        <span class="text-xs text-gray-600">
                            @if($experience->from)
                                {{ \Carbon\Carbon::parse($experience->from)->format('M Y') }}
                            @else
                                N/A
                            @endif
                            -
                            @if($experience->to)
                                {{ \Carbon\Carbon::parse($experience->to)->format('M Y') }}
                            @elseif($experience->is_present_job == 1)
                                Present
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    @if($experience->experience_description)
                    <p class="text-xs text-gray-700 mt-1">{{ $experience->experience_description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @php
            $qualifications = $jobSeeker->qualifications ? $jobSeeker->qualifications->filter(function($q) { return !isset($q->delete_status) || $q->delete_status == 0; }) : collect();
        @endphp
        @if($qualifications->count() > 0)
        <!-- Education (Qualifications) -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Education ({{ $qualifications->count() }})
            </h2>
            <div class="space-y-2">
                @foreach($qualifications as $qualification)
                <div class="border-l-3 border-green-500 pl-3 py-2 bg-gray-50 rounded-r">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-gray-900 text-sm">{{ $qualification->qualification ?? 'N/A' }}</span>
                        @if($qualification->subject)
                            <span class="text-gray-600 text-sm">in {{ $qualification->subject }}</span>
                        @endif
                        <span class="text-gray-500 text-sm">•</span>
                        <span class="text-gray-700 text-sm">{{ $qualification->university ?? 'N/A' }}</span>
                        @if($qualification->country_name && $qualification->country_name->name)
                            <span class="text-gray-500 text-sm">({{ $qualification->country_name->name }})</span>
                        @endif
                        @if($qualification->specialization)
                            <span class="text-gray-500 text-sm">•</span>
                            <span class="text-xs text-gray-600">Specialization: {{ $qualification->specialization }}</span>
                        @endif
                        <span class="text-gray-500 text-sm">•</span>
                        <span class="text-xs text-gray-600">
                            @if($qualification->join_year && $qualification->passing_year)
                                {{ $qualification->join_year }} - {{ $qualification->passing_year }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @php
            $skills = $jobSeeker->user_skills ? $jobSeeker->user_skills->filter(function($s) { return !isset($s->delete_status) || $s->delete_status == 0; }) : collect();
        @endphp
        @if($skills->count() > 0)
        <!-- Skills -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Skills ({{ $skills->count() }})
            </h2>
            <div class="flex flex-wrap gap-2">
                @foreach($skills as $skill)
                <div class="inline-flex items-center px-2 py-1 bg-purple-50 border border-purple-200 rounded-md">
                    <span class="font-medium text-gray-900 text-xs">{{ $skill->skillInfo->name ?? 'N/A' }}</span>
                    @if($skill->levelInfo && $skill->levelInfo->name)
                        <span class="ml-1 text-xs text-gray-600">({{ $skill->levelInfo->name }})</span>
                    @endif
                    @if($skill->year)
                        <span class="ml-1 text-xs text-gray-500">{{ $skill->year }}yr</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @php
            $languages = $jobSeeker->job_languages ? $jobSeeker->job_languages->filter(function($l) { return !isset($l->delete_status) || $l->delete_status == 0; }) : collect();
        @endphp
        @if($languages->count() > 0)
        <!-- Languages -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                Languages ({{ $languages->count() }})
            </h2>
            <div class="space-y-2">
                @foreach($languages as $language)
                <div class="border-l-3 border-yellow-500 pl-3 py-1.5 bg-gray-50 rounded-r">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-gray-900 text-sm">{{ $language->language ?? 'N/A' }}</span>
                        <span class="text-xs text-gray-600">
                            <span class="font-medium">Speaking:</span> {{ $language->speaking ?? 'N/A' }}
                        </span>
                        <span class="text-xs text-gray-600">
                            <span class="font-medium">Reading:</span> {{ $language->reading ?? 'N/A' }}
                        </span>
                        <span class="text-xs text-gray-600">
                            <span class="font-medium">Writing:</span> {{ $language->writing ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @php
            $appreciations = $jobSeeker->user_appreciations ? $jobSeeker->user_appreciations->filter(function($a) { return !isset($a->delete_status) || $a->delete_status == 0; }) : collect();
        @endphp
        @if($appreciations->count() > 0)
        <!-- Appreciation -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                Appreciation ({{ $appreciations->count() }})
            </h2>
            <div class="space-y-2">
                @foreach($appreciations as $appreciation)
                <div class="border-l-3 border-indigo-500 pl-3 py-1.5 bg-gray-50 rounded-r">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-gray-900 text-sm">{{ $appreciation->name ?? 'N/A' }}</span>
                        <span class="text-gray-600 text-sm">{{ $appreciation->organization ?? 'N/A' }}</span>
                        <span class="text-gray-500 text-sm">•</span>
                        <span class="text-xs text-gray-600">{{ $appreciation->month ?? 'N/A' }} {{ $appreciation->year ?? '' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Full Width Sections: Job Bookmarks and Applied Jobs -->
    @if($bookmarks && $bookmarks->count() > 0)
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            Job Bookmarks ({{ $bookmarks->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($bookmarks as $bookmark)
                @if($bookmark->job)
                    @php
                        $job = $bookmark->job;
                        $employer = $job->employer;
                        $position = $job->post ? $job->post->name : ($job->position ?? 'N/A');
                        $companyName = $employer ? $employer->company_name : 'N/A';
                        $location = '';
                        if ($employer) {
                            $locationParts = [];
                            if ($employer->company_city_data && $employer->company_city_data->name) {
                                $locationParts[] = $employer->company_city_data->name;
                            }
                            if ($employer->company_country_data && $employer->company_country_data->name) {
                                $locationParts[] = $employer->company_country_data->name;
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
                    @endphp
                    <a href="{{ route('admin.job-seekers.jobs.show', [$jobSeeker->id, $job->id]) }}" class="block bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-orange-200 relative">
                        <!-- Bookmark Icon -->
                        <div class="absolute top-3 right-3">
                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                            </svg>
                        </div>
                        
                        <!-- Company Logo Placeholder -->
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <!-- Job Title -->
                        <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $position }}</h3>
                        
                        <!-- Company Name -->
                        <p class="text-xs text-gray-600 mb-2">{{ $companyName }}</p>
                        
                        <!-- Job Type Badge -->
                        @if($jobType)
                        <div class="inline-flex items-center px-2 py-1 bg-yellow-100 rounded-md mb-2">
                            <svg class="w-3 h-3 text-yellow-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-xs font-medium text-yellow-800">{{ $jobType }}</span>
                        </div>
                        @endif
                        
                        <!-- Posted Time -->
                        <div class="flex items-center text-xs text-gray-500 mb-2">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $postedDate }}
                        </div>
                        
                        <!-- Location -->
                        <div class="flex items-center text-xs text-gray-600 mb-2">
                            <svg class="w-3 h-3 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $location }}
                        </div>
                        
                        <!-- Salary -->
                        @if($salary)
                        <div class="mt-2 pt-2 border-t border-orange-200">
                            <p class="text-sm font-bold text-green-600">{{ $salary }}</p>
                        </div>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    @if($appliedJobs && $appliedJobs->count() > 0)
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Applied Jobs ({{ $appliedJobs->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($appliedJobs as $application)
                @if($application->job)
                    @php
                        $job = $application->job;
                        $employer = $job->employer;
                        $position = $job->post ? $job->post->name : ($job->position ?? 'N/A');
                        $companyName = $employer ? $employer->company_name : 'N/A';
                        $location = '';
                        if ($employer) {
                            $locationParts = [];
                            if ($employer->company_city_data && $employer->company_city_data->name) {
                                $locationParts[] = $employer->company_city_data->name;
                            }
                            if ($employer->company_country_data && $employer->company_country_data->name) {
                                $locationParts[] = $employer->company_country_data->name;
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
                    @endphp
                    <a href="{{ route('admin.job-seekers.jobs.show', [$jobSeeker->id, $job->id]) }}" class="block bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-blue-200 relative">
                        <!-- Applied Badge -->
                        <div class="absolute top-3 right-3 flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs font-medium text-blue-700">Applied</span>
                        </div>
                        
                        <!-- Company Logo Placeholder -->
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <!-- Job Title -->
                        <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $position }}</h3>
                        
                        <!-- Company Name -->
                        <p class="text-xs text-gray-600 mb-2">{{ $companyName }}</p>
                        
                        <!-- Job Type Badge -->
                        @if($jobType)
                        <div class="inline-flex items-center px-2 py-1 bg-blue-100 rounded-md mb-2">
                            <svg class="w-3 h-3 text-blue-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-xs font-medium text-blue-800">{{ $jobType }}</span>
                        </div>
                        @endif
                        
                        <!-- Posted Time -->
                        <div class="flex items-center text-xs text-gray-500 mb-2">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $postedDate }}
                        </div>
                        
                        <!-- Location -->
                        <div class="flex items-center text-xs text-gray-600 mb-2">
                            <svg class="w-3 h-3 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $location }}
                        </div>
                        
                        <!-- Salary -->
                        @if($salary)
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <p class="text-sm font-bold text-green-600">{{ $salary }}</p>
                        </div>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
