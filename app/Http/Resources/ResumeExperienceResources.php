<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeExperienceResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //  return parent::toArray($request);


        return [
            'id' => $this->id,
            'country_id' => $this->country,
            'country_name' =>  $this->NullCheck($this->country_name, 'name'),
            // 'country_name' =>  $this->NullCheck($this->country_name, 'name'),
            'designation' => $this->designation,
            'company' => $this->company,
            'from_date' => $this->from,
            'to_date' => $this->to,
            'position_level' => $this->position_level,
            'is_present_job' => $this->is_present_job,
            'description'=>$this->experience_description,
            'delete_status' => $this->delete_status,

            
        ];
    }




    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
    }
}