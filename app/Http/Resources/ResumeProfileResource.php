<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeProfileResource extends JsonResource
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
            // 'firstName' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'district' => $this->district,
            'city'=>$this->city,
            'state' => $this->state,
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'height' => $this->height,
            'weight' => $this->weight,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
            'country' => $this->country,
            'country_info' =>  $this->NullCheck($this->user_country, 'name'),
            'religion_info' =>  $this->NullCheck($this->religion_info, 'name'),
            'marital_status_info' =>  $this->NullCheck($this->marital_status_info, 'name'),
            'gender_info' =>  $this->NullCheck($this->gender_info, 'name'),
            
            
        ];
    }


    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
     }
}