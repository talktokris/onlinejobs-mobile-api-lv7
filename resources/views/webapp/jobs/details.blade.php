@extends('layouts.app')

@section('title', 'Job Details')

@section('content')
<div class="space-y-4">
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

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.jobs.index') }}" class="mr-3 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Job Details</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.jobs.change-status', $job->id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 flex items-center shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Change Status
            </a>
            @if(isset($isApplied) && $isApplied)
        <div class="px-3 py-1 bg-orange-100 border border-orange-300 rounded-md flex items-center">
            <svg class="w-4 h-4 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-orange-800">Applied</span>
        </div>
        @endif
        </div>
    </div>

    <!-- Two Column Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left Column: Job Information Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-lg p-6">
            <!-- Company Logo and Basic Info -->
            <div class="text-center mb-6">
                @php
                    $employerId = $job->user_id;
                    $companyLogo = null;
                    if ($job->employer && $job->employer->company_logo) {
                        $logoPath = public_path('assets/user_images/' . $employerId . '/' . $job->employer->company_logo);
                        if (file_exists($logoPath)) {
                            $companyLogo = asset('assets/user_images/' . $employerId . '/' . $job->employer->company_logo);
                        }
                    }
                @endphp
                @if($companyLogo)
                    <a href="{{ route('admin.employers.show', $employerId) }}" class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white shadow-lg hover:shadow-xl transition duration-200 inline-block">
                        <img src="{{ $companyLogo }}" alt="Company Logo" class="w-full h-full object-cover">
                    </a>
                @else
                    <a href="{{ route('admin.employers.show', $employerId) }}" class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-lg hover:shadow-xl transition duration-200">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                @endif
                <h2 class="text-2xl font-bold text-gray-900 mb-1">
                    {{ $job->post ? $job->post->name : ($job->position ?? 'N/A') }}
                </h2>
                @if($job->employer)
                <a href="{{ route('admin.employers.show', $employerId) }}" class="text-gray-600 hover:text-blue-600 transition duration-200 inline-block">
                    {{ $job->employer->company_name ?? 'N/A' }}
                </a>
                @endif
            </div>

            <!-- Job Attributes -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                @if($job->job_vacancies_type)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="px-3 py-1 bg-white rounded-md text-sm text-gray-700 font-medium">{{ $job->job_vacancies_type }}</span>
                </div>
                @endif
                @php
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
                @endphp
                @if($location)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="px-3 py-1 bg-white rounded-md text-sm text-gray-700 font-medium">{{ $location }}</span>
                </div>
                @endif
                @if($job->created_at)
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">{{ $job->created_at->diffForHumans() }}</span>
                </div>
                @endif
                @if($job->salary_offer && is_numeric($job->salary_offer))
                <div class="flex items-center">
                    <span class="text-lg font-bold text-green-600">
                        {{ $job->salary_offer_currency ?: 'RM' }} {{ number_format((float)$job->salary_offer) }}
                        @if($job->salary_offer_period)
                            / {{ $job->salary_offer_period }}
                        @endif
                    </span>
                </div>
                @endif
            </div>

            <!-- Job Description -->
            @if($job->jobPointsDescriptions && $job->jobPointsDescriptions->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Job Description</h3>
                <ul class="space-y-2">
                    @foreach($job->jobPointsDescriptions as $description)
                    <li class="flex items-start bg-white rounded-md p-2">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 text-sm flex-1">{{ $description->point_details ?? '' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Requirements -->
            @if($job->jobPointsRequirements && $job->jobPointsRequirements->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Requirements</h3>
                <ul class="space-y-2">
                    @foreach($job->jobPointsRequirements as $requirement)
                    <li class="flex items-start bg-white rounded-md p-2">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 text-sm flex-1">{{ $requirement->point_details ?? '' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Right Column: Chat Thread Section -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Chat Thread
            </h3>

            @if(isset($messages) && $messages && $messages->count() > 0 && isset($jobSeeker) && $jobSeeker)
            <div class="space-y-3 mb-4" style="max-height: 600px; overflow-y: auto;">
                @foreach($messages as $message)
                <div class="flex {{ $message->sender_id == $jobSeeker->id ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == $jobSeeker->id ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-900 shadow-md border border-gray-200' }}">
                        <div class="text-xs font-medium mb-1 {{ $message->sender_id == $jobSeeker->id ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message->sender_id == $jobSeeker->id ? $jobSeeker->name : ($message->sender->name ?? 'Employer') }}
                        </div>
                        <div class="text-sm">{{ $message->message }}</div>
                        <div class="text-xs {{ $message->sender_id == $jobSeeker->id ? 'text-blue-200' : 'text-gray-500' }} mt-1">
                            {{ $message->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-gray-600">No messages yet</p>
                <p class="text-sm text-gray-500 mt-1">Start a conversation through the mobile app</p>
            </div>
            @endif

            <div class="border-t border-purple-200 pt-4 mt-4">
                <p class="text-xs text-gray-500 text-center flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Chat functionality is available through the mobile app
                </p>
            </div>
        </div>
    </div>

    <!-- Applicants Section -->
    @if(isset($applicants) && $applicants->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Applicants ({{ $applicants->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($applicants as $application)
                @if($application->user)
                    @php
                        $user = $application->user;
                        $profile = $user->user_profile_info;
                        $userImage = null;
                        if ($profile && $profile->image) {
                            $imagePath = public_path('assets/user_images/' . $user->id . '/' . $profile->image);
                            if (file_exists($imagePath)) {
                                $userImage = asset('assets/user_images/' . $user->id . '/' . $profile->image);
                            }
                        }
                        // Determine status text
                        $statusText = 'Applied';
                        $statusClass = 'bg-blue-100 text-blue-800';
                        if ($application->selection_status == 1) {
                            $statusText = 'Screening';
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                        } elseif ($application->selection_status == 2) {
                            $statusText = 'Selected';
                            $statusClass = 'bg-green-100 text-green-800';
                        } elseif ($application->selection_status == 3) {
                            $statusText = 'Hired';
                            $statusClass = 'bg-purple-100 text-purple-800';
                        }
                    @endphp
                    <a href="{{ route('admin.job-seekers.show', $user->id) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 p-4 border border-gray-200">
                        <!-- User Profile Image and Basic Info -->
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
                                <h3 class="font-bold text-gray-900 text-base mb-1 truncate">{{ $user->name ?? 'N/A' }}</h3>
                                <span class="inline-block px-2 py-1 {{ $statusClass }} text-xs font-medium rounded">
                                    Status: {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <!-- User Details Grid -->
                        @if($profile)
                        <div class="grid grid-cols-2 gap-2 text-xs mt-3">
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
                                        $heightStr = (string)$profile->height;
                                        $heightParts = explode('.', $heightStr);
                                        if (count($heightParts) === 2 && strlen($heightParts[1]) > 0) {
                                            $heightDisplay = $heightParts[0] . "'" . $heightParts[1] . '"';
                                        } else {
                                            $heightDisplay = $profile->height;
                                        }
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

                        <!-- Contact Information -->
                        <div class="mt-3 pt-3 border-t border-gray-200 space-y-1">
                            @if($profile && $profile->email)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span class="text-gray-900 ml-1 break-words">{{ $profile->email }}</span>
                            </div>
                            @elseif($user->email)
                            <div class="text-xs">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span class="text-gray-900 ml-1 break-words">{{ $user->email }}</span>
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
