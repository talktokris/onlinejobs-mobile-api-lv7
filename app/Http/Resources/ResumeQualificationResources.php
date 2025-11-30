<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeQualificationResources extends JsonResource
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
            'country_id' => $this->country,
            'country_name' =>  $this->NullCheck($this->country_name, 'name'),
            'qualification' => $this->qualification,
            'subject' => $this->subject,
            'specialization' => $this->specialization,
            'university' => $this->university,
            'join_year'=> $this->join_year,
            'passing_year' => $this->passing_year,
            'graduation_date' => $this->graduation_date,
            'delete_status' => $this->delete_status,
            'others' => $this->others,

        ];
    }

    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
    }
}