<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;
use App\Models\EmployerProfile;
use App\Services\ExpoNotificationService;
use DB;

use Validator;
use App\Http\Controllers\AppApi\AuthBaseController;

class UserMsgController extends AuthBaseController
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new ExpoNotificationService();
    }

    /**
     * Get all chat threads for job seeker
     * Job seekers can only see threads where they are the receiver
     */
    public function getThreads(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;

        // Get all unique threads where user is receiver
        $threads = Message::where('receiver_id', $user_id)
            ->where('message_type', 'chat')
            ->select('thread_id', 'sender_id', DB::raw('MAX(id) as last_message_id'), DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('thread_id', 'sender_id')
            ->orderBy('last_message_at', 'DESC')
            ->get();

        $threadData = [];
        foreach ($threads as $thread) {
            $sender = User::find($thread->sender_id);
            $lastMessage = Message::find($thread->last_message_id);

            // Get sender name - check User name first, then EmployerProfile company_name
            $senderName = 'Employer';
            if ($sender) {
                if (!empty($sender->name)) {
                    $senderName = $sender->name;
                } else {
                    // Check if sender is an employer and has company_name
                    $employerProfile = EmployerProfile::where('user_id', $sender->id)->first();
                    if ($employerProfile && !empty($employerProfile->company_name)) {
                        $senderName = $employerProfile->company_name;
                    }
                }
            }

            $threadData[] = [
                'thread_id' => $thread->thread_id,
                'sender_id' => $thread->sender_id,
                'sender_name' => $senderName,
                'last_message' => $lastMessage ? $lastMessage->message : '',
                'last_message_at' => $thread->last_message_at,
                'unread_count' => Message::where('thread_id', $thread->thread_id)
                    ->where('receiver_id', $user_id)
                    ->where('read_status', 0)
                    ->count(),
            ];
        }

        $response = [
            'success' => true,
            'data' => $threadData,
            'message' => 'Threads fetched successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Get messages in a specific thread
     */
    public function getThread(Request $request, $thread_id)
    {
        $user_id = auth('sanctum')->user()->id;

        // Verify user is part of this thread (as receiver)
        $thread = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->where('receiver_id', $user_id)
            ->first();

        if (!$thread) {
            return $this->sendError('Thread not found or access denied.');
        }

        // Get all messages in thread (exclude deleted messages)
        $messages = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->where('status', '!=', 0) // Exclude deleted messages
            ->orderBy('created_at', 'ASC')
            ->get();

        // Mark messages as read for user
        Message::where('thread_id', $thread_id)
            ->where('receiver_id', $user_id)
            ->where('read_status', 0)
            ->update(['read_status' => 1]);

        $messageData = [];
        foreach ($messages as $msg) {
            $messageData[] = [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'message' => $msg->message,
                'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                'humanDate' => $msg->created_at->diffForHumans(),
                'is_sender' => $msg->sender_id == $user_id,
            ];
        }

        $response = [
            'success' => true,
            'data' => $messageData,
            'message' => 'Thread messages fetched successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Reply to a message in an existing thread
     * Job seekers can only reply, not start new chats
     */
    public function replyMessage(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|string',
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $thread_id = $request->thread_id;
        $message_text = $request->message;

        // Verify thread exists and user is receiver (can only reply to existing threads)
        $existingThread = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->where('receiver_id', $user_id)
            ->first();

        if (!$existingThread) {
            return $this->sendError('Thread not found or access denied. Job seekers can only reply to existing messages.');
        }

        // Get sender ID (employer)
        $sender_id = $existingThread->sender_id;

        // Create reply message
        $message = new Message();
        $message->user_id = $sender_id; // For backward compatibility
        $message->sender_id = $user_id;
        $message->receiver_id = $sender_id;
        $message->thread_id = $thread_id;
        $message->parent_message_id = $existingThread->id;
        $message->message_type = 'chat';
        $message->job_id = $existingThread->job_id;
        $message->title = 'Chat Reply';
        $message->message = $message_text;
        $message->read_status = 0;
        $message->status = 1;
        $message->save();

        // Send push notification to employer
        $user = User::find($user_id);
        $notificationTitle = 'New Reply';
        $notificationBody = $user->name . ': ' . substr($message_text, 0, 50);
        $this->notificationService->sendToUser($sender_id, $notificationTitle, $notificationBody, [
            'type' => 'chat',
            'thread_id' => $thread_id,
            'job_id' => $existingThread->job_id
        ]);

        $response = [
            'success' => true,
            'data' => [
                'message_id' => $message->id,
                'thread_id' => $thread_id
            ],
            'message' => 'Reply sent successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Edit a message (only within 5 minutes of sending)
     */
    public function editMessage(Request $request, $messageId)
    {
        $user_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $message = Message::find($messageId);

        if (!$message) {
            return $this->sendError('Message not found.');
        }

        // Verify user is the sender
        if ($message->sender_id != $user_id) {
            return $this->sendError('You can only edit your own messages.');
        }

        // Check if message is within 5 minutes
        $createdAt = \Carbon\Carbon::parse($message->created_at);
        $now = \Carbon\Carbon::now();
        $diffMinutes = $createdAt->diffInMinutes($now);

        if ($diffMinutes > 5) {
            return $this->sendError('Messages can only be edited within 5 minutes of sending.');
        }

        // Update message
        $message->message = $request->message;
        $message->save();

        $response = [
            'success' => true,
            'data' => [
                'message_id' => $message->id,
                'message' => $message->message,
            ],
            'message' => 'Message edited successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Delete a message (only within 5 minutes of sending)
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $user_id = auth('sanctum')->user()->id;

        $message = Message::find($messageId);

        if (!$message) {
            return $this->sendError('Message not found.');
        }

        // Verify user is the sender
        if ($message->sender_id != $user_id) {
            return $this->sendError('You can only delete your own messages.');
        }

        // Check if message is within 5 minutes
        $createdAt = \Carbon\Carbon::parse($message->created_at);
        $now = \Carbon\Carbon::now();
        $diffMinutes = $createdAt->diffInMinutes($now);

        if ($diffMinutes > 5) {
            return $this->sendError('Messages can only be deleted within 5 minutes of sending.');
        }

        // Soft delete by setting status to 0
        $message->status = 0;
        $message->save();

        $response = [
            'success' => true,
            'data' => [
                'message_id' => $message->id,
            ],
            'message' => 'Message deleted successfully',
        ];

        return response()->json($response, 200);
    }
}

