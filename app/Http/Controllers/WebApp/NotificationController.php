<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ExpoPushNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    protected $expoService;

    public function __construct(ExpoPushNotificationService $expoService)
    {
        $this->expoService = $expoService;
    }

    /**
     * Display notification sending interface (redirects to blast)
     */
    public function index()
    {
        return redirect()->route('admin.notifications.show-blast');
    }

    /**
     * Display blast notification page
     */
    public function showBlast()
    {
        return view('webapp.notifications.blast');
    }

    /**
     * Display individual notification page
     */
    public function showIndividual()
    {
        return view('webapp.notifications.individual');
    }

    /**
     * Search users for individual notification
     */
    public function searchUsers(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:employer,job_seeker',
                'query' => 'required|string|min:2',
            ]);

            $roleId = $request->type === 'employer' ? 2 : 1;
            $searchTerm = trim($request->input('query', ''));

            // Remove any non-numeric characters from search term for phone search
            $numericSearch = preg_replace('/[^0-9]/', '', $searchTerm);

            $users = User::where('role_id', $roleId)
                ->where(function($query) use ($searchTerm, $numericSearch) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                    
                    // Search phone with both original and numeric-only versions
                    if (!empty($numericSearch)) {
                        $query->orWhere('phone', 'LIKE', "%{$numericSearch}%");
                    }
                    if ($numericSearch !== $searchTerm) {
                        $query->orWhere('phone', 'LIKE', "%{$searchTerm}%");
                    }
                })
                ->orderBy('name', 'asc')
                ->limit(20)
                ->get(['id', 'name', 'email', 'phone', 'expo_push_token', 'device_id']);

            return response()->json($users);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('User search error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while searching users',
                'message' => $e->getMessage()
            ], 500);
        }
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
            return redirect()->route('admin.notifications.show-blast')->withErrors(['message' => 'No users with valid push tokens found.']);
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
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }
        }

        $message = "Notifications sent: {$successCount} successful";
        if ($failCount > 0) {
            $message .= ", {$failCount} failed";
        }

        return redirect()->route('admin.notifications.show-blast')->with('success', $message);
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

        // Check if user has push token
        if (empty($user->expo_push_token)) {
            $errorMessage = 'User does not have a valid push token. ';
            if ($user->device_id) {
                $errorMessage .= 'The user has a device ID (' . $user->device_id . ') but the push token has not been registered yet. The user needs to open the mobile app and grant notification permissions to receive push notifications.';
            } else {
                $errorMessage .= 'The user needs to open the mobile app and register for push notifications to receive them.';
            }
            return redirect()->route('admin.notifications.show-individual')->withErrors(['message' => $errorMessage]);
        }

        $result = $this->expoService->sendToToken(
            $user->expo_push_token,
            $request->title,
            $request->message
        );

        if ($result['success']) {
            return redirect()->route('admin.notifications.show-individual')->with('success', 'Notification sent successfully to ' . ($user->name ?? 'User'));
        } else {
            return redirect()->route('admin.notifications.show-individual')->withErrors(['message' => 'Failed to send notification: ' . $result['message']]);
        }
    }
}

