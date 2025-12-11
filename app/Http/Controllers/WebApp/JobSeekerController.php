<?php

namespace App\Http\Controllers\WebApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobBookmark;
use App\Models\JobApplicant;
use App\Models\Job;
use App\Models\Profile;

class JobSeekerController extends Controller
{
    /**
     * Display a listing of job seekers
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', 1);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 1); // Default to active
        }

        $jobSeekers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('webapp.job-seekers.index', compact('jobSeekers'));
    }

    /**
     * Display the specified job seeker
     */
    public function show($id)
    {
        $jobSeeker = User::with(['profile', 'user_skills', 'educations', 'experiences', 'qualifications'])
            ->where('role_id', 1)
            ->findOrFail($id);

        // Get job bookmarks with jobs
        $bookmarkIds = JobBookmark::where('user_id', $id)
            ->where('delete_status', 0)
            ->pluck('job_id');
        
        $bookmarks = JobBookmark::where('user_id', $id)
            ->where('delete_status', 0)
            ->get();
        
        // Load jobs separately
        $jobs = Job::whereIn('id', $bookmarkIds)
            ->with('employer')
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
        
        // Load jobs separately
        $appliedJobsData = Job::whereIn('id', $appliedJobIds)
            ->with('employer')
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
}

