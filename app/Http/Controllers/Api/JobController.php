<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use DB;
use App\Models\JobLanguage;
use App\Models\JobAcademic;
use Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = DB::table('jobs')
            ->leftjoin('employer_profiles', 'jobs.user_id', '=', 'employer_profiles.user_id')
            ->select('jobs.id', 'jobs.positions_name', 'jobs.worker_type', 'jobs.total_number_of_vacancies', 'jobs.closing_date', 'employer_profiles.company_name', 'employer_profiles.id as employer_id', 'employer_profiles.company_logo', 'jobs.town', 'jobs.district', 'jobs.state')
            ->where('worker_type', '0')
            ->get()->take(10);
        return response()->json([
            'success' => true,
            'data' => $jobs,
            'message' => 'Jobs data fetch success'
        ], 200);
    }

    public function show($id)
    {
        $jobs = Job::where('worker_type', '0')
            ->where('jobs.id', $id)
            ->get();
        // $jobs=DB::table('jobs')
        //             ->leftjoin('employer_profiles','jobs.user_id','=','employer_profiles.user_id')
        //             // ->select('jobs.id','jobs.positions_name','jobs.worker_type','jobs.total_number_of_vacancies','jobs.closing_date','employer_profiles.company_name')
        //             ->where('worker_type','0')
        //             ->where('jobs.id',$id)
        //             ->get();
        return response()->json([
            'success' => true,
            'data' => $jobs,
            'message' => 'Jobs data fetch success'
        ], 200);
    }
    public function getEmployersJobs(Request $request)
    {
        $user_id = $request->user()->id;
        $jobs = Job::where('worker_type', '0')
            ->where('user_id', $user_id)
            ->get();
        return response()->json([
            'success' => true,
            'data' => $jobs,
            'message' => 'Employer Jobs data fetch success'
        ], 200);
    }

    public function saveJob(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'positions_name' => 'required'
        ]);
        if ($valid->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $valid->errors()
            ], 422);
        } else {
            $job = new Job;
            $job->user_id = auth()->id();
            $job->positions_name = $request->positions_name;
            $job->vacancies_description = $request->vacancies_description;
            $job->scope_of_duties = $request->scope_of_duties;
            $job->skills = $request->skills;
            $job->worker_type = $request->worker_type;
            $job->related_experience_year = $request->related_experience_year;
            $job->job_vacancies_type = $request->job_vacancies_type;
            $job->salary_offer_currency = $request->salary_offer_currency;
            $job->salary_offer = $request->salary_offer;
            $job->salary_offer_period = $request->salary_offer_period;
            $job->town = $request->town;
            $job->district = $request->district;
            $job->postcode = $request->postcode;
            $job->state = $request->state;
            $job->total_number_of_vacancies = $request->total_number_of_vacancies;
            $job->closing_date = $request->closing_date;
            $job->working_hours = $request->working_hours;
            $job->posted_by = $request->posted_by;
            $job->person_in_charge = $request->person_in_charge;
            $job->telephone_number = $request->telephone_number;
            $job->handphone_number = $request->handphone_number;
            $job->email = $request->email;
            $job->gender = $request->gender;
            $job->marital_status = $request->marital_status;
            $job->race = $request->race;
            $job->age_eligibillity = $request->age_eligibillity;
            $job->other_requirements = $request->other_requirements;
        //    $job->facilities = $request->facilities ? implode(", ", $request->facilities) : null;
            $job->minimum_academic_qualification = $request->minimum_academic_qualification;
            $job->academic_field = $request->academic_field;
            $job->driving_license = $request->driving_license;
            $job->other_skills = $request->other_skills;

            $job->save();

            // if($request->language && $request->language[0] != null){
            //     for($i=0; $i< count($request->language); $i++){
            //         $language = new JobLanguage;
            //         $language->job_id = $job->id;
            //         $language->language = $request->language[$i];
            //         $language->speaking = $request->speaking[$i];
            //         $language->writing = $request->writing[$i];
            //         $language->save();
            //     }
            // }

            // if($request->academic_qualifications && $request->academic_qualifications[0] != null){
            //     for($i=0; $i< count($request->academic_qualifications); $i++){
            //         $education = new JobAcademic;
            //         $education->job_id = $job->id;
            //         $education->academic_qualification = $request->academic_qualifications[$i];
            //         $education->academic_field = $request->academic_fields[$i];
            //         $education->save();
            //     }
            // }
            return response()->json([
                'success' => true,
                'message' => 'Post Job for JobSeeker Successfully'
            ], 200);
        }
    }
}