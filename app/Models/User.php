<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //  protected $guarded = [];


    /* --------------- Kris Code Start ---------*/
     
    protected $fillable = [
        'public_id',
        'name',
        'email',
        'phone',
        'status',
        'remember_token',
        'code',
        'last_name',
        'is_email_verified',
        'otp',
        'otp_expires_at',
        'new_mobile',
        'role_id',
        'country_id',
        'is_profile_completed',
        'expo_push_token',
        'device_id',
        'notification_enabled'
    ];

    /*----------- Kris Code End ---------------- */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    /* --------------- Kris Code Start ---------*/


    public function user_skills(){
        return $this->hasMany(UserSkill::class, 'user_id', 'id');
    }
    public function job_languages(){
        return $this->hasMany(JobLanguage::class, 'user_id', 'id');
    }

    public function user_appreciations(){
        return $this->hasMany(UserAppreciation::class, 'user_id', 'id');
    }
    public function education_profile(){
        return $this->hasMany(Qualification::class, 'user_id', 'id');
    }

    public function pro_experiences(){
        return $this->hasMany(ProfessionalExperience::class, 'user_id', 'id');
    }

    public function user_profile_info(){
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function resume_bookmarks(){
        return $this->hasMany(ResumeBookmark::class, 'user_id', 'id');
    }

    public function job_bookmarks(){
        return $this->hasMany(JobBookmark::class, 'user_id', 'id');
    }

    /* --------------- Kris Code End ---------*/




    public function agent_profile(){
        return $this->hasOne(AgentProfile::class);
    }

    public function employer_profile(){
        return $this->hasOne(EmployerProfile::class);
    }

    // public function country_employer_profile(){
    //     $user_country=UserProfile::where('user_id',auth()->user()->id)->pluck('company_country');
    //     $emp_filter=EmployerProfile::where('country',$user_country)->get();
    //     $user_filter= User::with('emp_filter')->where('status', 1)->whereRoleIs('employer')->get();
    //     return $user_filter;
    // }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function experiences(){
        return $this->hasMany(Experience::class);
    }
    public function educations(){
        return $this->hasMany(Education::class);
    }

    public function applicants(){
        return $this->hasMany(Applicant::class);
    }

    public function professional_profile(){
        return $this->hasOne(ProfessionalProfile::class);
    }
    public function retired_personnel(){
        return $this->hasOne(RetiredPersonnel::class);
    }
    public function retired_personnel_language(){
        return $this->hasMany(RetiredPersonnelsLanguage::class);
    }
    public function retired_personnel_experiences(){
        return $this->hasMany(RetiredPersonnelsWorkExperience::class);
    }
    public function retired_personnel_educations(){
        return $this->hasMany(RetiredPersonnelEducation::class);
    }
    public function professional_experiences(){
        return $this->hasMany(ProfessionalExperience::class);
    }
    public function qualifications(){
        return $this->hasMany(Qualification::class);
    }

    public function role(){
        return $this->hasOne(Role::class);
    }

    public function user_profile(){
        return $this->hasOne(UserProfile::class);
    }
    public function part_time_maid(){
        return $this->hasOne(Maid::class);
    }
    public function part_time_employer(){
        return $this->hasOne(PartTimeEmployer::class);
    }
    
    
}