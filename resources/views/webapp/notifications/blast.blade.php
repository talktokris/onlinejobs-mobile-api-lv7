@extends('layouts.app')

@section('title', 'Blast Notification')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Blast Notification</h1>

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

    @if(session('error') || $errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
                @if(session('error'))
                    <span class="block sm:inline">{{ session('error') }}</span>
                @endif
                @if($errors->any())
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Blast Notification Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border border-blue-100">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center mb-2">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                Blast Notification
            </h2>
            <p class="text-sm text-gray-600">Send notification to all users of a specific type</p>
        </div>

        <form method="POST" action="{{ route('admin.notifications.blast') }}" onsubmit="return confirm('Are you sure you want to send this notification to all selected users?');">
            @csrf

            <div class="space-y-5">
                <!-- Target Audience -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Target Audience
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="relative flex items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 cursor-pointer transition-all duration-200 shadow-sm hover:shadow-md">
                            <input type="radio" name="target" value="all_employers" required class="sr-only peer" {{ old('target') == 'all_employers' ? 'checked' : '' }}>
                            <div class="flex items-center flex-1">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">All Employers</div>
                                    <div class="text-xs text-gray-500">Send to all employers</div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 cursor-pointer transition-all duration-200 shadow-sm hover:shadow-md">
                            <input type="radio" name="target" value="all_job_seekers" required class="sr-only peer" {{ old('target') == 'all_job_seekers' ? 'checked' : '' }}>
                            <div class="flex items-center flex-1">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">All Job Seekers</div>
                                    <div class="text-xs text-gray-500">Send to all job seekers</div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 cursor-pointer transition-all duration-200 shadow-sm hover:shadow-md">
                            <input type="radio" name="target" value="all_users" required class="sr-only peer" {{ old('target') == 'all_users' ? 'checked' : '' }}>
                            <div class="flex items-center flex-1">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">All Users</div>
                                    <div class="text-xs text-gray-500">Send to everyone</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('target')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title Input -->
                <div>
                    <label for="blast_title" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        Title
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        @php
                            $titleBorderClass = $errors->has('title') ? 'border-red-500' : 'border-gray-200';
                        @endphp
                        <input type="text" 
                               name="title" 
                               id="blast_title" 
                               required 
                               maxlength="255"
                               value="{{ old('title') }}"
                               placeholder="Enter notification title..."
                               class="block w-full pl-12 pr-20 py-3.5 border-2 {{ $titleBorderClass }} rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="text-xs text-gray-400" id="blast_title_counter">0/255</span>
                        </div>
                    </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Input -->
                <div>
                    <label for="blast_message" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Message
                    </label>
                    <div class="relative">
                        @php
                            $messageBorderClass = $errors->has('message') ? 'border-red-500' : 'border-gray-200';
                        @endphp
                        <textarea name="message" 
                                  id="blast_message" 
                                  rows="6" 
                                  required 
                                  maxlength="1000"
                                  placeholder="Enter notification message..."
                                  class="block w-full px-4 py-3.5 pb-12 border-2 {{ $messageBorderClass }} rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 resize-y">{{ old('message') }}</textarea>
                        <div class="absolute bottom-3 right-3">
                            <span class="text-xs text-gray-400" id="blast_message_counter">0/1000</span>
                        </div>
                    </div>
                    @error('message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-blue-200">
                    <button type="submit" 
                            class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-semibold shadow-lg transform hover:scale-105 active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send Blast Notification
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Character counters
    function updateCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (input && counter) {
            const length = input.value.length;
            counter.textContent = `${length}/${maxLength}`;
            if (length > maxLength * 0.9) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-gray-400');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-gray-400');
            }
        }
    }

    // Initialize counters
    document.addEventListener('DOMContentLoaded', function() {
        updateCounter('blast_title', 'blast_title_counter', 255);
        updateCounter('blast_message', 'blast_message_counter', 1000);
    });

    // Add event listeners for counters
    ['blast_title', 'blast_message'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            const counterId = id + '_counter';
            const maxLength = id.includes('title') ? 255 : 1000;
            input.addEventListener('input', () => updateCounter(id, counterId, maxLength));
        }
    });
</script>

<style>
    /* Enhanced input focus effects */
    #blast_title:focus,
    #blast_message:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
</style>
@endsection
