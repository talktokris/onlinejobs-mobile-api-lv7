@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Job Ads</h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Search Form -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-100">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center mb-2">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Search Job Ads
            </h2>
            <p class="text-sm text-gray-600">Find jobs by position or company name</p>
        </div>
        
        <form method="GET" action="{{ route('admin.jobs.index') }}" id="searchForm" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Search Word Input -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Word
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ old('search', request('search', '')) }}" 
                               placeholder="Enter position or company name..." 
                               class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300">
                    </div>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Searches across position and company name fields
                    </p>
                </div>

                <!-- Status Dropdown -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <select name="status" 
                                id="status" 
                                class="block w-full pl-12 pr-10 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 appearance-none cursor-pointer">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Filter by active or inactive status
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-blue-200">
                @if(request()->anyFilled(['search', 'status', 'closing_date']))
                <a href="{{ route('admin.jobs.index') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200 font-medium shadow-sm border border-gray-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                </a>
                @endif
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-semibold shadow-lg transform hover:scale-105 active:scale-95 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Jobs List - Card Design -->
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
                        // Get first applicant's user_id for navigation, or use a default
                        $firstApplicant = \App\Models\JobApplicant::where('job_id', $job->id)->where('status', 1)->first();
                        if ($firstApplicant) {
                            $jobSeekerId = $firstApplicant->user_id;
                        } else {
                            $bookmark = \App\Models\JobBookmark::where('job_id', $job->id)->where('delete_status', 0)->first();
                            $jobSeekerId = $bookmark ? $bookmark->user_id : 1;
                        }
                    @endphp
                    <a href="{{ route('admin.job-seekers.jobs.show', [$jobSeekerId, $job->id]) }}" class="block bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-orange-200 relative">
                        <!-- Status Badge -->
                        @if($job->status == 1)
                        <div class="absolute top-3 right-3 flex items-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Running
                            </span>
                        </div>
                        @endif

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
                        <div class="mt-2 text-xs text-gray-600">
                            {{ $applicantsCount }} Applicant(s)
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No job ads found</p>
        @endif
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
    <div class="mt-4 bg-white rounded-lg shadow-lg p-4">
        <div class="flex justify-center">
            <div class="flex flex-wrap items-center justify-center gap-2">
                {{ $jobs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Results Count -->
    <div class="mt-2 text-sm text-gray-600">
        Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} job ads
    </div>

    <style>
        .pagination {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: wrap !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 0.5rem !important;
        }
        .pagination li {
            display: inline-block !important;
            margin: 0 !important;
        }
        .pagination .page-link {
            display: inline-block !important;
            padding: 0.5rem 0.75rem !important;
            margin: 0 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            color: #3b82f6 !important;
            background-color: #fff !important;
            text-decoration: none !important;
        }
        .pagination .page-link:hover {
            background-color: #f3f4f6 !important;
            border-color: #9ca3af !important;
        }
        .pagination .active .page-link {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }
        .pagination .disabled .page-link {
            color: #9ca3af !important;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }
    </style>

    <style>
        /* Enhanced input focus effects */
        #search:focus,
        #status:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        /* Custom select arrow */
        #status {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
    </style>
</div>
@endsection
