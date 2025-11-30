<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        // return parent::toArray($request);


        return [
            'id' => $this->id,
            'firstName' => $this->name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'mobileNo' => $this->phone,
            'countryId'=>$this->country_id,
            'roleId' => $this->role_id,
            'profile'=>  new ResumeProfileResource($this->user_profile_info),
            // 'employerProfile'=> $this->employer_profile,
            'employerProfile'=>  new EmployerDetailsResouces($this->employer_profile),

            

            // 'bookmarks'=>  $this->job_bookmarks,
            // 'bookmarks'=>  ResumeJobBookmarkResources::collection($this->job_bookmarks),

            // 'experiences'=>  ResumeExperienceResources::collection($this->pro_experiences),
            // 'qualifications'=>  ResumeQualificationResources::collection($this->qualifications),
            // 'languages'=>  ResumeLanguageResources::collection($this->job_languages),
            // 'skills'=>  ResumeSkillResources::collection($this->user_skills),
            // 'appreciation'=>  ResumeAppreciationResources::collection($this->user_appreciations),

            // 'device_name'=>$this->device_name,
            // 'app_margin_per'=>$this->app_margin_per,
            // 'role'=> new RoleResource($this->role),
            // 'country'=> new  CountryResource($this->country),
            // 'default_address'=> $this->address,
          //  'address_list'=> new UserAddressResource($this->address_list->user_id)
          
                //  'address_list'=> $this->address_list,
           // 'country'=>$this->country_id,
    
            
        ];
    }
}