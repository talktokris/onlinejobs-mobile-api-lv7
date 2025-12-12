<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\JobApplicant;
use App\Models\ResumeBookmark;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    /**
     * Display a listing of employers
     */
    public function index(Request $request)
    {
        // Start with base query for employers (role_id = 2)
        $query = User::where('role_id', 2)->with('employer_profile');

        // Search by search word (name, email, phone, company name) - Fixed search functionality
        $searchTerm = trim($request->input('search', ''));
        if (!empty($searchTerm)) {
            $searchPattern = '%' . $searchTerm . '%';
            $query->where(function($q) use ($searchPattern) {
                $q->where('name', 'LIKE', $searchPattern)
                  ->orWhere('email', 'LIKE', $searchPattern)
                  ->orWhere('phone', 'LIKE', $searchPattern)
                  ->orWhereHas('employer_profile', function($q) use ($searchPattern) {
                      $q->where('company_name', 'LIKE', $searchPattern);
                  });
            });
        }

        // Filter by status - Show all if not specified
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Order by latest first (most recent created_at) and paginate with 1000 results per page
        $employers = $query->orderBy('created_at', 'desc')->paginate(1000);

        return view('webapp.employers.index', compact('employers'));
    }

    /**
     * Display the specified employer
     */
    public function show($id)
    {
        // Load employer with all profile relationships
        $employer = User::with([
            'employer_profile.company_country_data',
            'employer_profile.company_city_data',
            'employer_profile.company_state_data'
        ])
        ->where('role_id', 2)
        ->findOrFail($id);

        // Get all job ads posted by this employer with relationships
        $jobs = Job::where('user_id', $id)
            ->where('status', 1)
            ->with([
                'employer.company_country_data',
                'employer.company_city_data',
                'employer.company_state_data',
                'post'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get resume bookmarks with user profile relationships
        $bookmarks = ResumeBookmark::where('employer_id', $id)
            ->where('delete_status', 0)
            ->with([
                'resume_details.user_profile_info.country_data',
                'resume_details.user_profile_info.state_data',
                'resume_details.user_profile_info.city_data',
                'resume_details.user_profile_info.gender_data',
                'resume_details.user_profile_info.marital_status_data',
                'resume_details.user_profile_info.religion_data'
            ])
            ->get();

        // Get all users who applied to employer's jobs with profile relationships
        $appliedUsers = JobApplicant::whereIn('job_id', $jobs->pluck('id'))
            ->where('status', 1)
            ->with([
                'user.user_profile_info.country_data',
                'user.user_profile_info.state_data',
                'user.user_profile_info.city_data',
                'user.user_profile_info.gender_data',
                'user.user_profile_info.marital_status_data',
                'user.user_profile_info.religion_data',
                'job.post'
            ])
            ->get()
            ->unique('user_id');

        return view('webapp.employers.show', compact('employer', 'jobs', 'bookmarks', 'appliedUsers'));
    }

    /**
     * Show job details from employer page
     */
    public function showJob($employerId, $jobId)
    {
        $employer = User::where('role_id', 2)->findOrFail($employerId);
        $job = Job::where('id', $jobId)
            ->where('user_id', $employerId)
            ->with(['employer', 'jobPointsRequirements', 'jobPointsDescriptions'])
            ->firstOrFail();

        // Get all applicants for this job
        $applicants = JobApplicant::where('job_id', $jobId)
            ->where('status', 1)
            ->with(['user', 'job'])
            ->get();

        return view('webapp.employers.job-details', compact('employer', 'job', 'applicants'));
    }

    /**
     * Show applicant details with messages
     */
    public function showApplicant($employerId, $jobId, $applicantId)
    {
        $employer = User::where('role_id', 2)->findOrFail($employerId);
        $job = Job::findOrFail($jobId);
        $applicant = User::with(['profile'])
            ->findOrFail($applicantId);

        // Verify applicant applied to this job
        $jobApplicant = JobApplicant::where('job_id', $jobId)
            ->where('user_id', $applicantId)
            ->firstOrFail();

        // Get message thread between employer and applicant
        // Find thread_id where both employer and applicant are involved
        $threadId = Message::where(function($query) use ($employerId, $applicantId) {
                $query->where(function($q) use ($employerId, $applicantId) {
                    $q->where('sender_id', $employerId)
                      ->where('receiver_id', $applicantId);
                })->orWhere(function($q) use ($employerId, $applicantId) {
                    $q->where('sender_id', $applicantId)
                      ->where('receiver_id', $employerId);
                });
            })
            ->where('job_id', $jobId)
            ->where('status', 1)
            ->value('thread_id');

        $messages = [];
        if ($threadId) {
            $messages = Message::where('thread_id', $threadId)
                ->where('status', 1)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('webapp.employers.applicant-details', compact('employer', 'job', 'applicant', 'jobApplicant', 'messages', 'threadId'));
    }

    /**
     * Show the form for editing the specified employer
     */
    public function edit($id)
    {
        $employer = User::with(['employer_profile'])
            ->where('role_id', 2)
            ->findOrFail($id);

        return view('webapp.employers.edit', compact('employer'));
    }

    /**
     * Update the specified employer
     */
    public function update(Request $request, $id)
    {
        $employer = User::where('role_id', 2)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Update user fields
        $employer->name = trim($request->name);
        $employer->email = trim($request->email);
        $employer->phone = $request->phone ? trim($request->phone) : null;
        $employer->status = $request->status;
        $employer->save();

        // Update or create employer profile
        $profile = EmployerProfile::firstOrNew(['user_id' => $id]);
        $profile->company_name = $request->company_name ? trim($request->company_name) : null;
        $profile->save();

        return redirect()->route('admin.employers.show', $id)->with('success', 'Employer updated successfully.');
    }

    /**
     * Show the form for changing job publish status from employer page
     */
    public function showChangePublish($employerId, $jobId)
    {
        $employer = User::with([
            'employer_profile.company_country_data',
            'employer_profile.company_city_data',
            'employer_profile.company_state_data'
        ])
            ->where('role_id', 2)
            ->findOrFail($employerId);
            
        $job = Job::where('id', $jobId)
            ->where('user_id', $employerId)
            ->with(['post'])
            ->firstOrFail();
        
        return view('webapp.employers.change-publish', compact('employer', 'job'));
    }

    /**
     * Update job publish status from employer page
     */
    public function updatePublish(Request $request, $employerId, $jobId)
    {
        $employer = User::where('role_id', 2)->findOrFail($employerId);
        $job = Job::where('id', $jobId)
            ->where('user_id', $employerId)
            ->firstOrFail();

        $request->validate([
            'publish_status' => 'required|in:0,1',
        ]);

        $job->publish_status = $request->publish_status;
        $job->save();

        return redirect()->route('admin.employers.show', $employerId)->with('success', 'Job publish status updated successfully.');
    }

    /**
     * Remove the specified employer
     */
    public function destroy($id)
    {
        $employer = User::where('role_id', 2)->findOrFail($id);
        $employer->status = 0;
        $employer->save();

        return redirect()->route('admin.employers.index')->with('success', 'Employer deleted successfully.');
    }
}

