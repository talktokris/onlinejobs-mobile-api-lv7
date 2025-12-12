@extends('layouts.app')

@section('title', 'Job Seekers')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Job Seekers</h1>
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
                Search Job Seekers
            </h2>
            <p class="text-sm text-gray-600">Find job seekers by name, email, phone, or status</p>
        </div>
        
        <form method="GET" action="{{ route('admin.job-seekers.index') }}" id="searchForm" class="space-y-5">
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
                               placeholder="Enter name, email, or phone number..." 
                               class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300">
                    </div>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Searches across name, email, and phone fields
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
                @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.job-seekers.index') }}" 
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

    <!-- Job Seekers List - Card Design -->
    @if($jobSeekers->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($jobSeekers as $jobSeeker)
            @php
                $profile = $jobSeeker->user_profile_info;
                $userImage = null;
                if ($profile && $profile->image) {
                    $imagePath = public_path('assets/user_images/' . $jobSeeker->id . '/' . $profile->image);
                    if (file_exists($imagePath)) {
                        $userImage = asset('assets/user_images/' . $jobSeeker->id . '/' . $profile->image);
                    }
                }
                
                // Check if bookmarked (check if any employer has bookmarked this resume)
                $isBookmarked = \App\Models\ResumeBookmark::where('user_id', $jobSeeker->id)
                    ->where('delete_status', 0)
                    ->exists();
                
                // Get latest bookmark date if exists
                $latestBookmark = \App\Models\ResumeBookmark::where('user_id', $jobSeeker->id)
                    ->where('delete_status', 0)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                // Format height
                $heightDisplay = 'N/A';
                if ($profile && $profile->height) {
                    $heightParts = explode('.', $profile->height);
                    $heightDisplay = count($heightParts) === 2 ? $heightParts[0] . "'" . $heightParts[1] . '"' : $profile->height;
                }
            @endphp
            <a href="{{ route('admin.job-seekers.show', $jobSeeker->id) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-gray-200 cursor-pointer">
                <!-- Profile Image and Name Section -->
                <div class="flex items-start mb-3">
                    @if($userImage)
                        <img src="{{ $userImage }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 mr-3 flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-base mb-1 truncate">{{ $jobSeeker->name ?? 'N/A' }}</h3>
                        @if($isBookmarked)
                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                            Bookmarked
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Personal Information - Two Columns -->
                @if($profile)
                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                    <!-- Left Column -->
                    <div class="space-y-2">
                        @if($profile->date_of_birth)
                        <div>
                            <span class="font-semibold text-gray-700">Date of Birth:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->date_of_birth }}</span>
                        </div>
                        @endif
                        @if($profile->marital_status_data && $profile->marital_status_data->name)
                        <div>
                            <span class="font-semibold text-gray-700">Marital Status:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->marital_status_data->name }}</span>
                        </div>
                        @endif
                        @if($profile->weight)
                        <div>
                            <span class="font-semibold text-gray-700">Weight:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->weight }}</span>
                        </div>
                        @endif
                        @if($profile->country_data && $profile->country_data->name)
                        <div>
                            <span class="font-semibold text-gray-700">Country:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->country_data->name }}</span>
                        </div>
                        @endif
                    </div>
                    <!-- Right Column -->
                    <div class="space-y-2">
                        @if($profile->gender_data && $profile->gender_data->name)
                        <div>
                            <span class="font-semibold text-gray-700">Gender:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->gender_data->name }}</span>
                        </div>
                        @endif
                        @if($profile->religion_data && $profile->religion_data->name)
                        <div>
                            <span class="font-semibold text-gray-700">Religion:</span>
                            <span class="text-gray-900 ml-1">{{ $profile->religion_data->name }}</span>
                        </div>
                        @endif
                        @if($profile->height)
                        <div>
                            <span class="font-semibold text-gray-700">Height:</span>
                            <span class="text-gray-900 ml-1">{{ $heightDisplay }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Contact Information -->
                <div class="mt-3 pt-3 border-t border-gray-200 space-y-1">
                    @if($profile && $profile->email)
                    <div class="text-xs">
                        <span class="font-semibold text-gray-700">Email:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->email }}</span>
                    </div>
                    @elseif($jobSeeker->email)
                    <div class="text-xs">
                        <span class="font-semibold text-gray-700">Email:</span>
                        <span class="text-gray-900 ml-1">{{ $jobSeeker->email }}</span>
                    </div>
                    @endif
                    @if($profile && $profile->phone)
                    <div class="text-xs">
                        <span class="font-semibold text-gray-700">Phone No:</span>
                        <span class="text-gray-900 ml-1">{{ $profile->phone }}</span>
                    </div>
                    @elseif($jobSeeker->phone)
                    <div class="text-xs">
                        <span class="font-semibold text-gray-700">Phone No:</span>
                        <span class="text-gray-900 ml-1">{{ $jobSeeker->phone }}</span>
                    </div>
                    @endif
                    @if($latestBookmark)
                    <div class="text-xs">
                        <span class="font-semibold text-gray-700">Bookmarked:</span>
                        <span class="text-gray-900 ml-1">{{ $latestBookmark->created_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 text-lg">No job seekers found</p>
    </div>
    @endif

    <!-- Pagination -->
    @if($jobSeekers->hasPages())
    <div class="mt-4 bg-white rounded-lg shadow-lg p-4">
        <div class="flex justify-center">
            <div class="flex flex-wrap items-center justify-center gap-2">
                {{ $jobSeekers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Results Count -->
    <div class="mt-2 text-sm text-gray-600">
        Showing {{ $jobSeekers->firstItem() ?? 0 }} to {{ $jobSeekers->lastItem() ?? 0 }} of {{ $jobSeekers->total() }} job seekers
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
        .date-error {
            border-color: #ef4444 !important;
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

