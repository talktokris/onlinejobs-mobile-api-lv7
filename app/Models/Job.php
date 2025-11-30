<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // Removed HasFactory trait - not available in Laravel 7

    protected $casts = [
        'closing_date' => 'datetime:d/m/Y'
    ];

    // Kris Relationship start

    public function jobPointsRequirements()
    {
        return $this->hasMany(JobDetailsPoint::class, 'job_id', 'id')->where('type',1);
    }


    public function jobPointsDescriptions()
    {
        return $this->hasMany(JobDetailsPoint::class, 'job_id', 'id')->where('type',2);
    }

    
    public function employer()
    {
        return $this->hasOne(EmployerProfile::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->hasOne(Option::class, 'id', 'positions_name');
    }

    public function user_profile_info(){
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function jobBookmarks(){
        return $this->hasMany(JobBookmark::class, 'job_id', 'id');
    }
  

    public function jobApplicantsAll(){
        return $this->hasMany(JobApplicant::class, 'job_id', 'id');
    }
  

    // Kris Relationship end

    public function languages()
    {
        return $this->hasMany(JobLanguage::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function academics()
    {
        return $this->hasMany(JobAcademic::class);
    }

    public function company()
    {
        return EmployerProfile::where('user_id', $this->user_id)->first();
    }

    public function jobApplicants()
    {
        return $this->hasMany(JobApplicant::class);
    }

    public function jobBlueWorkerApplicants()
    {
        return $this->hasMany(JobBlueColorApplicant::class);
    }

    
    public function alreadyApplied($user)
    {
        // dd($user);
        $applicants = $this->jobApplicants->pluck('user_id')->toArray();

        return in_array($user, $applicants);
    }

    public function alreadySelected($user)
    {
        $applicants = JobApplicant::where('invited_by_employer',2)->pluck('user_id')->toArray();

        return in_array($user, $applicants);
    }

    public function alreadyAppliedBlueWorker($user)
    {
        $job_blue_applicants = JobBlueColorApplicant::pluck('blue_color_id')->toArray();

        return in_array($user, $job_blue_applicants);
    }

    public function suggested_jobseekers()
    {
        return $this->jobApplicants->where('suggested_by_admin', true);
    }

    public function availableJobseekers()
    {
        $users = User::with('professional_profile')->where('status', 0)->whereRoleIs('professional')->get();
        $users = $users->reject(function ($user){
                    return $user->professional_profile['resume_headline'] != $this->positions_name || $this->alreadyApplied($user->id) || $this->alreadySelected($user->id);
                });
                // dd($users);
        return $users;
    }

    public function availableBlueWorker()
    {
        $blue_users = FastRegistratin::get();

        $blue_users = $blue_users->reject(function ($user){
                    return $user['job_category'] != $this->positions_name || $this->alreadyAppliedBlueWorker($user->id);
                });
        return $blue_users;
    }

    public static function recentJobs(){
      $recent_jobs = \DB::table('jobs as j')
        ->join('users as u', 'u.id', '=', 'j.user_id')
        ->join('options as o', 'o.id', '=', 'j.positions_name')
        ->join('employer_profiles as ep', 'ep.user_id', '=', 'u.id')
        ->leftJoin('countries as c','c.id','=','ep.company_country')
        ->leftJoin('states as s','s.id','=','ep.state')
        ->orderBy('j.id','desc')
        ->select(
            'u.id as u_id',
            'j.id as j_id',
            'ep.id as ep_id',
            'o.id as od_id',
            'ep.company_name', 
            'j.positions_name',
            'o.name as option_name',
            'j.total_number_of_vacancies',
            'j.total_number_of_vacancies',
            'j.related_experience_year',
            'j.closing_date',
            'ep.company_address',
            'c.name as country_name',
            's.name as state_name'
        )
        ->paginate(10);
        return $recent_jobs;
    }

    public function job_seeker_job_category_data(){
        return $this->belongsTo(Option::class, 'positions_name');
    }

    public static function recentJobsfilter($position_name){
        $recent_jobs = \DB::table('jobs as j')
        ->join('users as u', 'u.id', '=', 'j.user_id')
        ->join('options as o', 'o.id', '=', 'j.positions_name')
        ->join('employer_profiles as ep', 'ep.user_id', '=', 'u.id')
        ->leftJoin('countries as c','c.id','=','ep.company_country')
        ->leftJoin('states as s','s.id','=','ep.state')
        ->orderBy('j.id','desc')
        // ->where('j.positions_name','LIKE','%'.$position_name.'%')
        ->where('o.name','LIKE','%'.$position_name.'%')
        ->select(
            'u.id as u_id',
            'j.id as j_id',
            'ep.id as ep_id',
            'ep.company_name', 
            'j.positions_name',
            'o.name as option_name',
            'j.total_number_of_vacancies',
            'j.total_number_of_vacancies',
            'j.related_experience_year',
            'j.closing_date',
            'ep.company_address',
            'c.name as country_name',
            's.name as state_name'
        )
        ->paginate(10);
        return $recent_jobs;
    }

    public static function recentJobsfilterBylocation($location_name){
        $recent_jobs = \DB::table('jobs as j')
        ->join('users as u', 'u.id', '=', 'j.user_id')
        ->join('options as o', 'o.id', '=', 'j.positions_name')
        ->join('employer_profiles as ep', 'ep.user_id', '=', 'u.id')
        ->leftJoin('countries as c','c.id','=','ep.company_country')
        ->leftJoin('states as s','s.id','=','ep.state')
        ->orderBy('j.id','desc')
        ->where('c.name','LIKE','%'.$location_name.'%')
        ->orWhere('s.name','LIKE','%'.$location_name.'%')
        ->select(
            'u.id as u_id',
            'j.id as j_id',
            'ep.id as ep_id',
            'ep.company_name', 
            'j.positions_name',
            'o.name as option_name',
            'j.total_number_of_vacancies',
            'j.total_number_of_vacancies',
            'j.related_experience_year',
            'j.closing_date',
            'ep.company_address',
            'c.name as country_name',
            's.name as state_name'
        )
        ->paginate(10);
        return $recent_jobs;
    }

    public static function recentJobsDetails($id){
        $recent_jobs = \DB::table('jobs as j')
        ->join('users as u', 'u.id', '=', 'j.user_id')
        ->join('options as o', 'o.id', '=', 'j.positions_name')
        ->join('employer_profiles as ep', 'ep.user_id', '=', 'u.id')
        ->leftJoin('countries as c','c.id','=','ep.company_country')
        ->leftJoin('states as s','s.id','=','ep.state')
        ->leftJoin('cities as ct','ct.id','=','ep.company_city')
        ->where('j.id',$id)
        // ->where('ep.user_id',$id)
        // ->where('u.id',$id)
        ->select(
            'u.id',
            'ep.company_name', 
            'j.positions_name',
            'j.total_number_of_vacancies',
            'j.related_experience_year',
            'j.closing_date',
            'ep.company_address',
            'c.name as country_name',
            'ct.name as city_name',
            's.name as state_name',
            'j.id as job_id',
            'o.name as option_name',
            'j.salary_offer_currency',
            'j.salary_offer',
            'j.telephone_number',
            'j.email',
            'j.skills',
            'j.related_experience_year',
            'j.job_vacancies_type',
            'j.vacancies_description',
            'j.scope_of_duties',
            'j.total_number_of_vacancies',
            'j.working_hours',
            'j.age_eligibillity',
            'j.other_requirements',
            'j.facilities',
            'j.minimum_academic_qualification',
            'j.academic_field',
            'j.driving_license',
            'j.other_skills',
            'j.other_facilities'
            )
        ->first();
        return $recent_jobs;
    }
}