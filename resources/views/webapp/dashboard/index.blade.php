@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Job Seekers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_job_seekers']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Employers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_employers']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Jobs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Applications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Messages</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_messages']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users (30 days)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_users']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Registration Trend -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registration Trend</h3>
            <canvas id="userRegistrationChart"></canvas>
        </div>

        <!-- Job Postings Trend -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Postings Trend</h3>
            <canvas id="jobPostingChart"></canvas>
        </div>
    </div>

    <!-- Applications Trend -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Applications Trend</h3>
        <canvas id="applicationChart"></canvas>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Users</h3>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $user->role_id == 1 ? 'Job Seeker' : 'Employer' }}</p>
                        </div>
                        <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent users</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Jobs</h3>
            <div class="space-y-3">
                @forelse($recentJobs as $job)
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $job->position ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $job->employer->company_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent jobs</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Applications</h3>
            <div class="space-y-3">
                @forelse($recentApplications as $application)
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $application->job->position ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent applications</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    // User Registration Chart
    const userRegCtx = document.getElementById('userRegistrationChart').getContext('2d');
    new Chart(userRegCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($userRegistrationTrend, 'month')),
            datasets: [
                {
                    label: 'Job Seekers',
                    data: @json(array_column($userRegistrationTrend, 'job_seekers')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Employers',
                    data: @json(array_column($userRegistrationTrend, 'employers')),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Job Posting Chart
    const jobPostCtx = document.getElementById('jobPostingChart').getContext('2d');
    new Chart(jobPostCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($jobPostingTrend, 'month')),
            datasets: [{
                label: 'Jobs Posted',
                data: @json(array_column($jobPostingTrend, 'count')),
                backgroundColor: 'rgba(147, 51, 234, 0.5)',
                borderColor: 'rgb(147, 51, 234)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Application Chart
    const appCtx = document.getElementById('applicationChart').getContext('2d');
    new Chart(appCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($applicationTrend, 'month')),
            datasets: [{
                label: 'Applications',
                data: @json(array_column($applicationTrend, 'count')),
                borderColor: 'rgb(234, 179, 8)',
                backgroundColor: 'rgba(234, 179, 8, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

