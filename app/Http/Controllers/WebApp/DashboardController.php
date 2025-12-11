<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\JobApplicant;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        // Statistics
        $stats = [
            'total_job_seekers' => User::where('role_id', 1)->where('status', 1)->count(),
            'total_employers' => User::where('role_id', 2)->where('status', 1)->count(),
            'total_jobs' => Job::where('status', 1)->count(),
            'total_applications' => JobApplicant::where('status', 1)->count(),
            'total_messages' => Message::where('status', 1)->count(),
            'active_users' => User::where('status', 1)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->count(),
        ];

        // User registration trend (last 6 months)
        $userRegistrationTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $userRegistrationTrend[] = [
                'month' => $month->format('M Y'),
                'job_seekers' => User::where('role_id', 1)
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
                'employers' => User::where('role_id', 2)
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ];
        }

        // Job postings trend (last 6 months)
        $jobPostingTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $jobPostingTrend[] = [
                'month' => $month->format('M Y'),
                'count' => Job::whereBetween('created_at', [$start, $end])->count(),
            ];
        }

        // Job applications trend (last 6 months)
        $applicationTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            
            $applicationTrend[] = [
                'month' => $month->format('M Y'),
                'count' => JobApplicant::whereBetween('created_at', [$start, $end])->count(),
            ];
        }

        // Recent Activity
        $recentUsers = User::whereIn('role_id', [1, 2])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentJobs = Job::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->with('employer')
            ->get();

        $recentApplications = JobApplicant::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->with(['user', 'job'])
            ->get();

        return view('webapp.dashboard.index', compact(
            'stats',
            'userRegistrationTrend',
            'jobPostingTrend',
            'applicationTrend',
            'recentUsers',
            'recentJobs',
            'recentApplications'
        ));
    }
}

