@extends('layouts.app')

@section('title', 'Individual Notification')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Individual Notification</h1>

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

    <!-- Individual Notification Section -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl shadow-lg p-6 border border-purple-100">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center mb-2">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Individual Notification
            </h2>
            <p class="text-sm text-gray-600">Send notification to a specific user</p>
        </div>

        <form method="POST" action="{{ route('admin.notifications.individual') }}">
            @csrf

            <div class="space-y-5">
                <!-- Search Type and User Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Search Type -->
                    <div>
                        <label for="search_type" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Type
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <select name="search_type" 
                                    id="search_type" 
                                    class="block w-full pl-12 pr-10 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 appearance-none cursor-pointer">
                                <option value="employer" {{ old('search_type') == 'employer' ? 'selected' : '' }}>Search Employer</option>
                                <option value="job_seeker" {{ old('search_type') == 'job_seeker' ? 'selected' : '' }}>Search Job Seeker</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- User Search -->
                    <div>
                        <label for="user_search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search User
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="user_search" 
                                   placeholder="Type name, email, or phone..."
                                   value="{{ old('user_search') }}"
                                   class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300">
                            <input type="hidden" name="user_id" id="selected_user_id" required value="{{ old('user_id') }}">
                            <div id="search_results" class="absolute z-10 w-full mt-1 bg-white border-2 border-purple-200 rounded-lg shadow-xl max-h-64 overflow-y-auto hidden"></div>
                        </div>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="push_token_warning" class="mt-2 hidden"></div>
                        <div id="selected_user_display" class="mt-2 hidden">
                            <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-semibold text-gray-900" id="selected_user_name"></div>
                                        <div class="text-xs text-gray-500" id="selected_user_email"></div>
                                    </div>
                                </div>
                                <button type="button" onclick="clearSelectedUser()" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div>
                    <label for="individual_title" class="block text-sm font-semibold text-gray-700 mb-2">
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
                        <input type="text" 
                               name="title" 
                               id="individual_title" 
                               required 
                               maxlength="255"
                               value="{{ old('title') }}"
                               placeholder="Enter notification title..."
                               class="block w-full pl-12 pr-20 py-3.5 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 @error('title') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="text-xs text-gray-400" id="individual_title_counter">0/255</span>
                        </div>
                    </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Input -->
                <div>
                    <label for="individual_message" class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Message
                    </label>
                    <div class="relative">
                        <textarea name="message" 
                                  id="individual_message" 
                                  rows="6" 
                                  required 
                                  maxlength="1000"
                                  placeholder="Enter notification message..."
                                  class="block w-full px-4 py-3.5 pb-12 border-2 border-gray-200 rounded-lg shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-sm bg-white hover:border-gray-300 resize-y @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        <div class="absolute bottom-3 right-3">
                            <span class="text-xs text-gray-400" id="individual_message_counter">0/1000</span>
                        </div>
                    </div>
                    @error('message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-purple-200">
                    <button type="submit" 
                            class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 font-semibold shadow-lg transform hover:scale-105 active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send Individual Notification
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let searchTimeout;
    const searchInput = document.getElementById('user_search');
    const searchResults = document.getElementById('search_results');
    const selectedUserId = document.getElementById('selected_user_id');
    const searchType = document.getElementById('search_type');
    const selectedUserDisplay = document.getElementById('selected_user_display');
    const selectedUserName = document.getElementById('selected_user_name');
    const selectedUserEmail = document.getElementById('selected_user_email');

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
        updateCounter('individual_title', 'individual_title_counter', 255);
        updateCounter('individual_message', 'individual_message_counter', 1000);
    });

    // Add event listeners for counters
    ['individual_title', 'individual_message'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            const counterId = id + '_counter';
            const maxLength = id.includes('title') ? 255 : 1000;
            input.addEventListener('input', () => updateCounter(id, counterId, maxLength));
        }
    });

    // User search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                const type = searchType.value;
                const url = `{{ route('admin.notifications.search-users') }}?type=${type}&query=${encodeURIComponent(query)}`;
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Non-JSON response:', text);
                                throw new Error('Server returned non-JSON response');
                            });
                        }
                        if (!response.ok) {
                            return response.json().then(err => {
                                const errorMsg = err.error || err.message || 'Unknown error';
                                throw new Error(errorMsg);
                            }).catch(() => {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(users => {
                        // Handle error responses
                        if (users && users.error) {
                            console.error('Search error:', users.error);
                            searchResults.innerHTML = '<div class="p-4 text-sm text-red-500 text-center">' + (users.message || users.error) + '</div>';
                            searchResults.classList.remove('hidden');
                            return;
                        }
                        
                        if (!Array.isArray(users)) {
                            console.error('Invalid response format:', users);
                            searchResults.innerHTML = '<div class="p-4 text-sm text-red-500 text-center">Invalid response from server</div>';
                            searchResults.classList.remove('hidden');
                            return;
                        }
                        
                        if (users.length === 0) {
                            searchResults.innerHTML = '<div class="p-4 text-sm text-gray-500 text-center">No users found</div>';
                            searchResults.classList.remove('hidden');
                            return;
                        }

                        let html = '';
                        users.forEach(user => {
                            const userName = (user.name || 'N/A').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const userEmail = (user.email || 'N/A').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const userPhone = (user.phone || 'N/A').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const hasPushToken = user.expo_push_token && user.expo_push_token.trim() !== '';
                            const hasDeviceId = user.device_id && user.device_id.trim() !== '';
                            const tokenStatus = hasPushToken ? 
                                '<span class="inline-block ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded">Push Token ✓</span>' : 
                                (hasDeviceId ? '<span class="inline-block ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">No Push Token</span>' : 
                                '<span class="inline-block ml-2 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded">Not Registered</span>');
                            html += `<div class="p-3 hover:bg-purple-50 cursor-pointer border-b border-gray-200 transition-colors ${!hasPushToken ? 'bg-yellow-50' : ''}" onclick="selectUser(${user.id}, '${userName}', '${userEmail}', '${userPhone}', ${hasPushToken})">
                                <div class="font-semibold text-gray-900 flex items-center">${user.name || 'N/A'}${tokenStatus}</div>
                                <div class="text-sm text-gray-500">${user.email || 'N/A'}</div>
                                ${user.phone ? `<div class="text-xs text-gray-400">${user.phone}</div>` : ''}
                            </div>`;
                        });
                        searchResults.innerHTML = html;
                        searchResults.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="p-4 text-sm text-red-500 text-center">Error searching users. Please try again.</div>';
                        searchResults.classList.remove('hidden');
                    });
            }, 300);
        });
    }

    function selectUser(userId, userName, userEmail, userPhone, hasPushToken) {
        selectedUserId.value = userId;
        searchInput.value = userName;
        selectedUserName.textContent = userName;
        selectedUserEmail.textContent = userEmail + (userPhone && userPhone !== 'N/A' ? ' • ' + userPhone : '');
        selectedUserDisplay.classList.remove('hidden');
        searchResults.classList.add('hidden');
        searchInput.classList.add('bg-purple-50');
        
        // Show warning if no push token
        const warningDiv = document.getElementById('push_token_warning');
        if (warningDiv) {
            if (!hasPushToken) {
                warningDiv.classList.remove('hidden');
                warningDiv.innerHTML = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert"><div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg><span class="block sm:inline">Warning: This user does not have a push token registered. They need to open the mobile app and grant notification permissions to receive push notifications.</span></div></div>';
            } else {
                warningDiv.classList.add('hidden');
                warningDiv.innerHTML = '';
            }
        }
    }

    function clearSelectedUser() {
        selectedUserId.value = '';
        searchInput.value = '';
        selectedUserDisplay.classList.add('hidden');
        searchInput.classList.remove('bg-purple-50');
        const warningDiv = document.getElementById('push_token_warning');
        if (warningDiv) {
            warningDiv.classList.add('hidden');
            warningDiv.innerHTML = '';
        }
    }

    // Hide results when clicking outside
    document.addEventListener('click', function(event) {
        if (searchInput && searchResults && !searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Update search when type changes
    if (searchType) {
        searchType.addEventListener('change', function() {
            if (searchInput && searchInput.value.trim().length >= 2) {
                searchInput.dispatchEvent(new Event('input'));
            }
        });
    }
</script>

<style>
    /* Enhanced input focus effects */
    #individual_title:focus,
    #individual_message:focus,
    #user_search:focus,
    #search_type:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.15);
    }
    
    /* Custom select arrow */
    #search_type {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>
@endsection
