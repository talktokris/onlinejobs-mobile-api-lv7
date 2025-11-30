<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicantResources extends JsonResource
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
                'applied_by_jobseeker'=>$this->applied_by_jobseeker,
                'invited_by_employer'=>$this->invited_by_employer,
                'suggested_by_admin'=>$this->invited_by_employer,
                'status'=>$this->invited_by_employer,
                'interview_date'=>$this->invited_by_employer,
                'hiring_date'=>$this->invited_by_employer,
        ];
        
        }
}