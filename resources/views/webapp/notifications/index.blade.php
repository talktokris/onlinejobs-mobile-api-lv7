@extends('layouts.app')

@section('title', 'Send Notification')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Send Push Notification</h1>

    <!-- Blast Notification Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Blast Notification</h2>
        <p class="text-sm text-gray-600 mb-4">Send notification to all users of a specific type</p>

        <form method="POST" action="{{ route('admin.notifications.blast') }}" onsubmit="return confirm('Are you sure you want to send this notification to all selected users?');">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="target" value="all_employers" required class="mr-2">
                            <span>All Employers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="target" value="all_job_seekers" required class="mr-2">
                            <span>All Job Seekers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="target" value="all_users" required class="mr-2">
                            <span>All Users</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="blast_title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="blast_title" required maxlength="255"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="blast_message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" id="blast_message" rows="4" required maxlength="1000"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('message') border-red-500 @enderror"></textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Send Blast Notification
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Individual Notification Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Individual Notification</h2>
        <p class="text-sm text-gray-600 mb-4">Send notification to a specific user</p>

        <form method="POST" action="{{ route('admin.notifications.individual') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="search_type" class="block text-sm font-medium text-gray-700">Search Type</label>
                    <select name="search_type" id="search_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="employer">Search Employer</option>
                        <option value="job_seeker">Search Job Seeker</option>
                    </select>
                </div>

                <div>
                    <label for="user_search" class="block text-sm font-medium text-gray-700">Search User</label>
                    <input type="text" id="user_search" placeholder="Type name, email, or phone..." 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="hidden" name="user_id" id="selected_user_id" required>
                    <div id="search_results" class="mt-2 hidden border border-gray-300 rounded-md max-h-48 overflow-y-auto bg-white"></div>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="individual_title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="individual_title" required maxlength="255"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="individual_message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" id="individual_message" rows="4" required maxlength="1000"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('message') border-red-500 @enderror"></textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            selectedUserId.value = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            const type = searchType.value;
            fetch(`{{ route('admin.notifications.search-users') }}?type=${type}&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    if (users.length === 0) {
                        searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No users found</div>';
                        searchResults.classList.remove('hidden');
                        return;
                    }

                    let html = '';
                    users.forEach(user => {
                        html += `<div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200" onclick="selectUser(${user.id}, '${user.name || 'N/A'}', '${user.email || 'N/A'}')">
                            <div class="font-medium">${user.name || 'N/A'}</div>
                            <div class="text-sm text-gray-500">${user.email || 'N/A'} - ${user.phone || 'N/A'}</div>
                        </div>`;
                    });
                    searchResults.innerHTML = html;
                    searchResults.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="p-3 text-sm text-red-500">Error searching users</div>';
                    searchResults.classList.remove('hidden');
                });
        }, 300);
    });

    function selectUser(userId, userName, userEmail) {
        selectedUserId.value = userId;
        searchInput.value = `${userName} (${userEmail})`;
        searchResults.classList.add('hidden');
    }

    // Hide results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.add('hidden');
        }
    });
</script>
@endsection

