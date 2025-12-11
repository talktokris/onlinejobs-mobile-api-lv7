{{-- Message Chat Component --}}
@if(isset($messages) && $messages->count() > 0)
    <div class="space-y-4 max-h-96 overflow-y-auto bg-gray-50 p-4 rounded-lg">
        @foreach($messages as $message)
            <div class="flex {{ $message->sender_id == $senderId ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == $senderId ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-gray-200' }}">
                    <div class="text-sm font-medium mb-1">
                        {{ $message->sender->name ?? 'User' }}
                    </div>
                    <div class="text-sm">{{ $message->message }}</div>
                    <div class="text-xs mt-1 {{ $message->sender_id == $senderId ? 'opacity-75' : 'text-gray-500' }}">
                        {{ $message->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500 text-center py-8">No messages found</p>
@endif

