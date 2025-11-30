<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JobDetailsResources;

class EmployerJobListingResources extends JsonResource
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
            'job_id' => $this->id,
            'company_name'=>$this->company_name,
            'positions_name' => $this->positions_name,
            'worker_type' => $this->worker_type,
            'job_vacancies_type'=>$this->job_vacancies_type,
            'salary_offer_currency'=>$this->salary_offer_currency,
            'salary_offer'=>$this->salary_offer,
            'salary_offer_period'=>$this->salary_offer_period,
            'total_number_of_vacancies' => $this->total_number_of_vacancies,
            'closing_date' => $this->closing_date,
            'humanDate'=>$this->created_at->diffForHumans(),
            // 'closing_date2' => parse($this->closing_date)->diffForHumans(),
            
            'working_hours'=>$this->working_hours,

            'gender'=>$this->gender,
            'marital_status'=>$this->marital_status,
            'race'=>$this->race,
            'age_eligibillity'=>$this->age_eligibillity,
            'other_requirements'=>$this->other_requirements,
            'minimum_academic_qualification'=>$this->minimum_academic_qualification,
            'academic_field'=>$this->academic_field,
            'driving_license'=>$this->driving_license,
            'description_text'=>$this->vacancies_description,
            'delete_status'=>$this->delete_status,
            'publish_status'=>$this->publish_status,

            'position' =>  $this->NullCheck($this->post, 'name'), 
            'employer'=>  new JobEmployerResources($this->employer),
 

            'descriptions'=>  JobDetailsResources::collection($this->jobPointsDescriptions),
            'requirements'=>  JobDetailsResources::collection($this->jobPointsRequirements),


   
            'applicants'=>  EmployerJobApplicantResources::collection($this->jobApplicantsAll),
            // 'bookmarks'=>  $this->jobBookmarks,
            // 'applicants'=>  $this->jobApplicantsAll,
            // 'requirements'=> $this->jobPointsRequirements,

            // 'descriptions2'=> $this->jobPointsDescriptions,
            // 'requirements2'=> $this->jobPointsRequirements,
            
            // 'topCommnets'=>$this->points($this->job_points_descriptions),
            // 'job_points_requirements'=> JobDetailsResources::collection($this->job_points_requirements),

            // 'job_points_descriptions'=>$this->job_points_descriptions,
            // 'job_points_requirements'=>$this->job_points_requirements,

            // 'role'=> new RoleResource($this->role),
            // 'country'=> new  CountryResource($this->country),
            // 'default_address'=> $this->address,
          //  'address_list'=> new UserAddressResource($this->address_list->user_id)
          
                //  'address_list'=> $this->address_list,
           // 'country'=>$this->country_id,
    
            
        ];
    }


    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
    }
}