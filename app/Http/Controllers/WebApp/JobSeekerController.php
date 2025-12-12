<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobBookmark;
use App\Models\JobApplicant;
use App\Models\Job;
use App\Models\Profile;
use App\Models\Message;

class JobSeekerController extends Controller
{
    /**
     * Display a listing of job seekers
     */
    public function index(Request $request)
    {
        // Start with base query for job seekers (role_id = 1)
        $query = User::where('role_id', 1);

        // Search by search word (name, email, phone) - Fixed search functionality
        $searchTerm = trim($request->input('search', ''));
        if (!empty($searchTerm)) {
            $searchPattern = '%' . $searchTerm . '%';
            $query->where(function($q) use ($searchPattern) {
                $q->where('name', 'LIKE', $searchPattern)
                  ->orWhere('email', 'LIKE', $searchPattern)
                  ->orWhere('phone', 'LIKE', $searchPattern);
            });
        }

        // Filter by status
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Order by latest first (most recent created_at) and paginate with 1000 results per page
        $jobSeekers = $query->orderBy('created_at', 'desc')->paginate(1000);

        return view('webapp.job-seekers.index', compact('jobSeekers'));
    }

    /**
     * Display the specified job seeker
     */
    public function show($id)
    {
        // Load job seeker with all relationships matching mobile app structure
        $jobSeeker = User::with([
            'user_profile_info.country_data', 
            'user_profile_info.state_data', 
            'user_profile_info.city_data',
            'user_profile_info.gender_data',
            'user_profile_info.marital_status_data',
            'user_profile_info.religion_data',
            'user_profile_info.user_country',
            'professional_experiences.country_name',
            'qualifications.country_name',
            'job_languages',
            'user_skills.skillInfo',
            'user_skills.levelInfo',
            'user_appreciations'
        ])
        ->where('role_id', 1)
        ->findOrFail($id);

        // Get job bookmarks with jobs
        $bookmarkIds = JobBookmark::where('user_id', $id)
            ->where('delete_status', 0)
            ->pluck('job_id');
        
        $bookmarks = JobBookmark::where('user_id', $id)
            ->where('delete_status', 0)
            ->get();
        
        // Load jobs separately with more relationships
        $jobs = Job::whereIn('id', $bookmarkIds)
            ->with([
                'employer.company_country_data',
                'employer.company_city_data',
                'employer.company_state_data',
                'post'
            ])
            ->get()
            ->keyBy('id');
        
        // Attach jobs to bookmarks
        foreach ($bookmarks as $bookmark) {
            $bookmark->job = $jobs->get($bookmark->job_id);
        }

        // Get applied jobs
        $appliedJobIds = JobApplicant::where('user_id', $id)
            ->where('status', 1)
            ->pluck('job_id');
        
        $appliedJobs = JobApplicant::where('user_id', $id)
            ->where('status', 1)
            ->get();
        
        // Load jobs separately with more relationships
        $appliedJobsData = Job::whereIn('id', $appliedJobIds)
            ->with([
                'employer.company_country_data',
                'employer.company_city_data',
                'employer.company_state_data',
                'post'
            ])
            ->get()
            ->keyBy('id');
        
        // Attach jobs to applications
        foreach ($appliedJobs as $application) {
            $application->job = $appliedJobsData->get($application->job_id);
        }

        return view('webapp.job-seekers.show', compact('jobSeeker', 'bookmarks', 'appliedJobs'));
    }

    /**
     * Show the form for editing the specified job seeker
     */
    public function edit($id)
    {
        $jobSeeker = User::with(['profile', 'user_skills', 'educations', 'experiences', 'qualifications'])
            ->where('role_id', 1)
            ->findOrFail($id);

        return view('webapp.job-seekers.edit', compact('jobSeeker'));
    }

    /**
     * Update the specified job seeker
     */
    public function update(Request $request, $id)
    {
        $jobSeeker = User::where('role_id', 1)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:0,1',
        ]);

        $jobSeeker->name = $request->name;
        $jobSeeker->email = $request->email;
        $jobSeeker->phone = $request->phone;
        $jobSeeker->status = $request->status;
        $jobSeeker->save();

        return redirect()->route('admin.job-seekers.show', $id)->with('success', 'Job seeker updated successfully.');
    }

    /**
     * Remove the specified job seeker
     */
    public function destroy($id)
    {
        $jobSeeker = User::where('role_id', 1)->findOrFail($id);
        $jobSeeker->status = 0;
        $jobSeeker->save();

        return redirect()->route('admin.job-seekers.index')->with('success', 'Job seeker deleted successfully.');
    }

    /**
     * Show job details with chat thread for a job seeker
     */
    public function showJob($jobSeekerId, $jobId)
    {
        // Verify job seeker exists
        $jobSeeker = User::where('role_id', 1)->findOrFail($jobSeekerId);
        
        // Load job with all relationships
        $job = Job::with([
            'employer.company_country_data',
            'employer.company_city_data',
            'employer.company_state_data',
            'post',
            'jobPointsDescriptions',
            'jobPointsRequirements'
        ])->findOrFail($jobId);
        
        // Get chat thread for this job and job seeker
        // Thread ID format: emp_{employer_id}_user_{job_seeker_id}
        $employerId = $job->user_id;
        $threadId = 'emp_' . $employerId . '_user_' . $jobSeekerId;
        
        $messages = Message::where('thread_id', $threadId)
            ->where('message_type', 'chat')
            ->where('job_id', $jobId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Check if job is bookmarked by this user
        $isBookmarked = JobBookmark::where('user_id', $jobSeekerId)
            ->where('job_id', $jobId)
            ->where('delete_status', 0)
            ->exists();
        
        // Check if job is applied by this user
        $isApplied = JobApplicant::where('user_id', $jobSeekerId)
            ->where('job_id', $jobId)
            ->where('status', 1)
            ->exists();
        
        // Get all applicants for this job with profile relationships
        $applicants = JobApplicant::where('job_id', $jobId)
            ->where('status', 1)
            ->with([
                'user.user_profile_info.country_data',
                'user.user_profile_info.state_data',
                'user.user_profile_info.city_data',
                'user.user_profile_info.gender_data',
                'user.user_profile_info.marital_status_data',
                'user.user_profile_info.religion_data'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('webapp.job-seekers.job-details', compact('jobSeeker', 'job', 'messages', 'threadId', 'isBookmarked', 'isApplied', 'applicants'));
    }
}

