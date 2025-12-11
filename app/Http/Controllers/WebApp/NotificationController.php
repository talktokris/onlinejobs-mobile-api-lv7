<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ExpoPushNotificationService;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $expoService;

    public function __construct(ExpoPushNotificationService $expoService)
    {
        $this->expoService = $expoService;
    }

    /**
     * Display notification sending interface
     */
    public function index()
    {
        return view('webapp.notifications.index');
    }

    /**
     * Search users for individual notification
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'type' => 'required|in:employer,job_seeker',
            'query' => 'required|string|min:2',
        ]);

        $roleId = $request->type === 'employer' ? 2 : 1;

        $users = User::where('role_id', $roleId)
            ->where('status', 1)
            ->where(function($query) use ($request) {
                $query->where('name', 'like', "%{$request->query}%")
                      ->orWhere('email', 'like', "%{$request->query}%")
                      ->orWhere('phone', 'like', "%{$request->query}%");
            })
            ->whereNotNull('expo_push_token')
            ->where('expo_push_token', '!=', '')
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone', 'expo_push_token']);

        return response()->json($users);
    }

    /**
     * Send blast notification
     */
    public function sendBlast(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all_employers,all_job_seekers,all_users',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $roleIds = [];
        if ($request->target === 'all_employers') {
            $roleIds = [2];
        } elseif ($request->target === 'all_job_seekers') {
            $roleIds = [1];
        } else {
            $roleIds = [1, 2];
        }

        // Get all users with valid push tokens
        $users = User::whereIn('role_id', $roleIds)
            ->where('status', 1)
            ->whereNotNull('expo_push_token')
            ->where('expo_push_token', '!=', '')
            ->get();

        if ($users->isEmpty()) {
            return back()->withErrors(['message' => 'No users with valid push tokens found.']);
        }

        // Extract tokens
        $tokens = $users->pluck('expo_push_token')->toArray();

        // Send notifications in batches (Expo recommends max 100 per request)
        $batches = array_chunk($tokens, 100);
        $successCount = 0;
        $failCount = 0;

        foreach ($batches as $batch) {
            $result = $this->expoService->sendNotification(
                $batch,
                $request->title,
                $request->message
            );

            if ($result['success']) {
                $successCount += count($batch);
            } else {
                $failCount += count($batch);
                Log::error('Blast notification batch failed', [
                    'batch_size' => count($batch),
                    'error' => $result['message']
                ]);
            }
        }

        $message = "Notifications sent: {$successCount} successful";
        if ($failCount > 0) {
            $message .= ", {$failCount} failed";
        }

        return back()->with('success', $message);
    }

    /**
     * Send individual notification
     */
    public function sendIndividual(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($request->user_id);

        if (empty($user->expo_push_token)) {
            return back()->withErrors(['message' => 'User does not have a valid push token.']);
        }

        $result = $this->expoService->sendToToken(
            $user->expo_push_token,
            $request->title,
            $request->message
        );

        if ($result['success']) {
            return back()->with('success', 'Notification sent successfully to ' . ($user->name ?? 'User'));
        } else {
            return back()->withErrors(['message' => 'Failed to send notification: ' . $result['message']]);
        }
    }
}

