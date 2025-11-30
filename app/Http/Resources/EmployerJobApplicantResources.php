<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerJobApplicantResources extends JsonResource
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


        return 
        [
               
                'id' => $this->id,
                'user_id' => $this->user_id,
                'user_profile' => new  EmployerResumeProfileResource($this->applicantProfile),
                // 'user_profile2' => $this->applicantProfile,
                
                'user_basic'=>$this->userBasic($this->applicantUser),
                // 'user_basic_all'=>$this->applicantUser,
                'applied_by_jobseeker'=>$this->applied_by_jobseeker,
                'invited_by_employer'=>$this->invited_by_employer,
                'suggested_by_admin'=>$this->invited_by_employer,
                'status'=>$this->invited_by_employer,
                'selection_status'=>$this->selection_status,
                'interview_date'=>$this->invited_by_employer,
                'hiring_date'=>$this->invited_by_employer,
        ];
    }

    public function userBasic($data)
    {
         if($data==null){  return $data ;} else { 
            
            return [
                'first_name'=>$data->name,
                'last_name'=>$data->last_name,
                'name'=>$data->name ." ".$data->last_name,
                'role_id'=>$data->role_id,
                'phone'=>$data->phone,
                'email'=>$data->email,
            ];
        
        }
 
    }
}