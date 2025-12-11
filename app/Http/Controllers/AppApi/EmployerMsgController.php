<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Country;
use App\Models\Message;
use App\Models\Job;
use App\Services\ExpoNotificationService;
use DB;

use Validator;
use App\Http\Resources\MessageResource;

use App\Http\Controllers\AppApi\FillsAppController;
use App\Http\Controllers\AppApi\AuthBaseController;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\EmployerProfileResource;

class EmployerMsgController extends AuthBaseController
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new ExpoNotificationService();
    }

    /**
     * Start a new chat thread with a job seeker
     * Only employers can start chats
     */
    public function startChat(Request $request)
    {
        $employer_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|integer|min:1',
            'message' => 'required|string|min:1|max:1000',
            'job_id' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $receiver_id = $request->receiver_id;
        $message_text = $request->message;
        $job_id = $request->job_id ?? null;

        // Verify receiver exists
        $receiver = User::where('id', $receiver_id)->first();

        if (!$receiver) {
            return $this->sendError('Receiver not found.');
        }

        // Check if receiver has job seeker role (role_id = 1)
        // First check role_user table (Laratrust)
        $hasJobSeekerRole = DB::table('role_user')
            ->where('user_id', $receiver_id)
            ->where('role_id', 1)
            ->exists();

        // Also check if user has role_id field directly (fallback)
        if (!$hasJobSeekerRole && isset($receiver->role_id) && $receiver->role_id == 1) {
            $hasJobSeekerRole = true;
        }

        if (!$hasJobSeekerRole) {
            return $this->sendError('Invalid receiver. Only job seekers can receive messages from employers.');
        }

        // Generate thread ID
        $thread_id = 'emp_' . $employer_id . '_user_' . $receiver_id;

        // Check if thread already exists
        $existingThread = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->first();

        if ($existingThread) {
            return $this->sendError('Chat thread already exists. Use send message endpoint instead.');
        }

        // Create the first message
        $message = new Message();
        $message->user_id = $receiver_id; // For backward compatibility
        $message->sender_id = $employer_id;
        $message->receiver_id = $receiver_id;
        $message->thread_id = $thread_id;
        $message->parent_message_id = null;
        $message->message_type = 'chat';
        $message->job_id = $job_id;
        $message->title = 'New Chat';
        $message->message = $message_text;
        $message->read_status = 0;
        $message->status = 1;
        $message->save();

        // Send push notification to job seeker
        $employer = User::find($employer_id);
        $notificationTitle = 'New Message';
        $notificationBody = $employer->name . ' sent you a message';
        $this->notificationService->sendToUser($receiver_id, $notificationTitle, $notificationBody, [
            'type' => 'chat',
            'thread_id' => $thread_id,
            'job_id' => $job_id
        ]);

        $response = [
            'success' => true,
            'data' => [
                'thread_id' => $thread_id,
                'message_id' => $message->id
            ],
            'message' => 'Chat started successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Get all chat threads for employer
     */
    public function getThreads(Request $request)
    {
        $employer_id = auth('sanctum')->user()->id;

        // Get all unique threads where employer is sender
        $threads = Message::where('sender_id', $employer_id)
            ->where('message_type', 'chat')
            ->select('thread_id', 'receiver_id', DB::raw('MAX(id) as last_message_id'), DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('thread_id', 'receiver_id')
            ->orderBy('last_message_at', 'DESC')
            ->get();

        $threadData = [];
        foreach ($threads as $thread) {
            $receiver = User::find($thread->receiver_id);
            $lastMessage = Message::find($thread->last_message_id);

            // Get receiver name (job seeker) - use 'User' as fallback if name is empty
            $receiverName = 'User';
            if ($receiver && !empty($receiver->name)) {
                $receiverName = $receiver->name;
            }

            $threadData[] = [
                'thread_id' => $thread->thread_id,
                'receiver_id' => $thread->receiver_id,
                'receiver_name' => $receiverName,
                'last_message' => $lastMessage ? $lastMessage->message : '',
                'last_message_at' => $thread->last_message_at,
                'unread_count' => Message::where('thread_id', $thread->thread_id)
                    ->where('receiver_id', $employer_id)
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
        $employer_id = auth('sanctum')->user()->id;

        // Verify employer is part of this thread
        $thread = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->where(function ($query) use ($employer_id) {
                $query->where('sender_id', $employer_id)
                    ->orWhere('receiver_id', $employer_id);
            })
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

        // Mark messages as read for employer
        Message::where('thread_id', $thread_id)
            ->where('receiver_id', $employer_id)
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
                'is_sender' => $msg->sender_id == $employer_id,
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
     * Send a message in an existing thread
     */
    public function sendMessage(Request $request)
    {
        $employer_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|string',
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $thread_id = $request->thread_id;
        $message_text = $request->message;

        // Verify thread exists and employer is part of it
        $existingThread = Message::where('thread_id', $thread_id)
            ->where('message_type', 'chat')
            ->where(function ($query) use ($employer_id) {
                $query->where('sender_id', $employer_id)
                    ->orWhere('receiver_id', $employer_id);
            })
            ->first();

        if (!$existingThread) {
            return $this->sendError('Thread not found or access denied.');
        }

        // Get receiver ID
        $receiver_id = $existingThread->sender_id == $employer_id 
            ? $existingThread->receiver_id 
            : $existingThread->sender_id;

        // Create new message
        $message = new Message();
        $message->user_id = $receiver_id; // For backward compatibility
        $message->sender_id = $employer_id;
        $message->receiver_id = $receiver_id;
        $message->thread_id = $thread_id;
        $message->parent_message_id = null;
        $message->message_type = 'chat';
        $message->job_id = $existingThread->job_id;
        $message->title = 'Chat Message';
        $message->message = $message_text;
        $message->read_status = 0;
        $message->status = 1;
        $message->save();

        // Send push notification
        $employer = User::find($employer_id);
        $notificationTitle = 'New Message';
        $notificationBody = $employer->name . ': ' . substr($message_text, 0, 50);
        $this->notificationService->sendToUser($receiver_id, $notificationTitle, $notificationBody, [
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
            'message' => 'Message sent successfully',
        ];

        return response()->json($response, 200);
    }

    /**
     * Edit a message (within 2 minutes of sending)
     */
    public function editMessage(Request $request)
    {
        $employer_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'message_id' => 'required|integer|min:1',
            'message' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $message_id = $request->message_id;
        $new_message_text = $request->message;

        // Find the message
        $message = Message::where('id', $message_id)
            ->where('message_type', 'chat')
            ->where('sender_id', $employer_id) // Only sender can edit
            ->first();

        if (!$message) {
            return $this->sendError('Message not found or you do not have permission to edit it.');
        }

        // Check if message was sent within last 5 minutes
        $messageTime = new \DateTime($message->created_at);
        $now = new \DateTime();
        $diffMinutes = ($now->getTimestamp() - $messageTime->getTimestamp()) / 60;

        if ($diffMinutes > 5) {
            return $this->sendError('Message can only be edited within 5 minutes of sending.');
        }

        // Update message
        $message->message = $new_message_text;
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
     * Delete a message (within 2 minutes of sending)
     */
    public function deleteMessage(Request $request)
    {
        $employer_id = auth('sanctum')->user()->id;

        $validator = Validator::make($request->all(), [
            'message_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $message_id = $request->message_id;

        // Find the message
        $message = Message::where('id', $message_id)
            ->where('message_type', 'chat')
            ->where('sender_id', $employer_id) // Only sender can delete
            ->first();

        if (!$message) {
            return $this->sendError('Message not found or you do not have permission to delete it.');
        }

        // Check if message was sent within last 5 minutes
        $messageTime = new \DateTime($message->created_at);
        $now = new \DateTime();
        $diffMinutes = ($now->getTimestamp() - $messageTime->getTimestamp()) / 60;

        if ($diffMinutes > 5) {
            return $this->sendError('Message can only be deleted within 5 minutes of sending.');
        }

        // Soft delete or hard delete - using soft delete by setting status
        $message->status = 0; // Mark as deleted
        $message->save();

        // Or hard delete: $message->delete();

        $response = [
            'success' => true,
            'data' => [
                'message_id' => $message_id,
            ],
            'message' => 'Message deleted successfully',
        ];

        return response()->json($response, 200);
    }

    public function employerMessages(Request $request)
    {
             $user_id = auth('sanctum')->user()->id;
            //  return $user_id;


            //  $user_id=222;
   
           $messageData = Message::where('user_id','=', $user_id)->orderBy('id', 'DESC')->limit(200)->get();
   
           $status= true;
           $message = 'Message fetched successfully';
   
           
           $response = [
               'status' => $status,
               'data'    => MessageResource::collection($messageData),
               'message' => $message,
           ];
           return response()->json($response, 200);

    }



    public function employerMessageReadCount(Request $request){
        
        
      $user_id = auth('sanctum')->user()->id;
   //  $user_id=222;

      $messageDataCount = Message::where('user_id','=', $user_id)->where('read_status','=', 0)->orderBy('id', 'DESC')->limit(200)->get()->count();

      $status= true;
      $message = 'Message fetched successfully';

      
      $response = [
          'status' => $status,
          'data'    => $messageDataCount,
          'message' => $message,
      ];
      return response()->json($response, 200);
      
  }

  public function employerMessageReadUpdate(Request $request){
      
      
      $user_id = auth('sanctum')->user()->id;

      $validator = Validator::make($request->all(), [
          'id' => 'required|integer|min:0|max:9999999',
          
          ]);

          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());       
          }

          $data= $request->all();
          $message_id= $data['id'];

          $updateItem = Message::where('id', '=',$message_id)->update(['read_status'=> 1]);

          if(!$updateItem){   $success=false;   $get_id = $message_id; $message='Unknown Error, Plz Contact support'; }
          else{   $success=true; $get_id = $message_id; $message='Message Seen successfully'; }

          $response = [
              'success' => $success,
              'data'    => $get_id,
              'message' => $message,
          ];
          return response()->json($response, 200);
      
  }

}