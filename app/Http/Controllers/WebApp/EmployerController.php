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
        $query = User::where('role_id', 2)->with('employer_profile');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('employer_profile', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 1); // Default to active
        }

        $employers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('webapp.employers.index', compact('employers'));
    }

    /**
     * Display the specified employer
     */
    public function show($id)
    {
        $employer = User::with(['employer_profile'])
            ->where('role_id', 2)
            ->findOrFail($id);

        // Get all job ads posted by this employer
        $jobs = Job::where('user_id', $id)
            ->where('status', 1)
            ->with('employer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get resume bookmarks
        $bookmarks = ResumeBookmark::where('employer_id', $id)
            ->where('delete_status', 0)
            ->with('resume_details')
            ->get();

        // Get all users who applied to employer's jobs
        $appliedUsers = JobApplicant::whereIn('job_id', $jobs->pluck('id'))
            ->where('status', 1)
            ->with(['user', 'job'])
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
            'status' => 'required|in:0,1',
        ]);

        $employer->name = $request->name;
        $employer->email = $request->email;
        $employer->phone = $request->phone;
        $employer->status = $request->status;
        $employer->save();

        // Update employer profile if company name is provided
        if ($request->filled('company_name')) {
            $profile = EmployerProfile::firstOrNew(['user_id' => $id]);
            $profile->company_name = $request->company_name;
            $profile->save();
        }

        return redirect()->route('admin.employers.show', $id)->with('success', 'Employer updated successfully.');
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

