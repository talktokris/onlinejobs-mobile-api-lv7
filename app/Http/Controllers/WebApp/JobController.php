<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplicant;
use App\Models\Message;
use App\Models\User;

class JobController extends Controller
{
    /**
     * Display a listing of jobs
     */
    public function index(Request $request)
    {
        // Start with base query for jobs with relationships
        $query = Job::with([
            'employer.company_country_data',
            'employer.company_city_data',
            'employer.company_state_data',
            'post'
        ]);

        // Search by search word (position, company name) - Fixed search functionality
        $searchTerm = trim($request->input('search', ''));
        if (!empty($searchTerm)) {
            $searchPattern = '%' . $searchTerm . '%';
            $query->where(function($q) use ($searchPattern) {
                $q->where('position', 'LIKE', $searchPattern)
                  ->orWhereHas('post', function($q) use ($searchPattern) {
                      $q->where('name', 'LIKE', $searchPattern);
                  })
                  ->orWhereHas('employer', function($q) use ($searchPattern) {
                      $q->where('company_name', 'LIKE', $searchPattern);
                  });
            });
        }

        // Filter by status - Show all if not specified
        $status = $request->input('status');
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        // Filter by closing date
        if ($request->filled('closing_date')) {
            $query->whereDate('closing_date', $request->closing_date);
        }

        // Order by latest first and paginate with 1000 results per page
        $jobs = $query->orderBy('created_at', 'desc')->paginate(1000);

        return view('webapp.jobs.index', compact('jobs'));
    }

    /**
     * Display the specified job
     */
    public function show($id)
    {
        $job = Job::with(['employer', 'jobPointsRequirements', 'jobPointsDescriptions', 'user'])
            ->findOrFail($id);

        // Get employer user
        $employer = User::find($job->user_id);

        // Get all applicants for this job
        $applicants = JobApplicant::where('job_id', $id)
            ->where('status', 1)
            ->with(['user', 'job'])
            ->get();

        return view('webapp.jobs.show', compact('job', 'employer', 'applicants'));
    }

    /**
     * Show applicant details with messages
     */
    public function showApplicant($jobId, $applicantId)
    {
        $job = Job::with(['employer'])->findOrFail($jobId);
        $applicant = User::with(['profile'])->findOrFail($applicantId);
        $employer = User::find($job->user_id);

        // Verify applicant applied to this job
        $jobApplicant = JobApplicant::where('job_id', $jobId)
            ->where('user_id', $applicantId)
            ->firstOrFail();

        // Get message thread between employer and applicant
        $threadId = Message::where(function($query) use ($employer, $applicant) {
                $query->where(function($q) use ($employer, $applicant) {
                    $q->where('sender_id', $employer->id)
                      ->where('receiver_id', $applicant->id);
                })->orWhere(function($q) use ($employer, $applicant) {
                    $q->where('sender_id', $applicant->id)
                      ->where('receiver_id', $employer->id);
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

        return view('webapp.jobs.applicant-details', compact('job', 'applicant', 'employer', 'jobApplicant', 'messages', 'threadId'));
    }

    /**
     * Show the form for editing the specified job
     */
    public function edit($id)
    {
        $job = Job::with(['employer', 'jobPointsRequirements', 'jobPointsDescriptions'])
            ->findOrFail($id);

        return view('webapp.jobs.edit', compact('job'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $request->validate([
            'position' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $job->position = $request->position;
        $job->status = $request->status;
        if ($request->filled('closing_date')) {
            $job->closing_date = $request->closing_date;
        }
        $job->save();

        return redirect()->route('admin.jobs.show', $id)->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->status = 0;
        $job->save();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}

