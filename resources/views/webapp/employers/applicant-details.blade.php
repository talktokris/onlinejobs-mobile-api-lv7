@extends('layouts.app')

@section('title', 'Applicant Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Applicant Details</h1>
        <a href="{{ route('admin.employers.jobs.show', [$employer->id, $job->id]) }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Back to Job
        </a>
    </div>

    <!-- Applicant Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Applicant Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $applicant->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $applicant->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $applicant->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Applied Job</label>
                <p class="mt-1 text-sm text-gray-900">{{ $job->position ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Applied Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $jobApplicant->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Message Chat History -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Message Chat History</h2>
        @if($messages && $messages->count() > 0)
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id == $employer->id ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == $employer->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                            <div class="text-sm font-medium mb-1">
                                {{ $message->sender_id == $employer->id ? $employer->name : $applicant->name }}
                            </div>
                            <div class="text-sm">{{ $message->message }}</div>
                            <div class="text-xs mt-1 opacity-75">
                                {{ $message->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No messages found</p>
        @endif
    </div>
</div>
@endsection

