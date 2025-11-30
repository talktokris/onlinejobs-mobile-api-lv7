<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobEmployerResources extends JsonResource
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
            'profile_id' => $this->id,
            'employer_id' => $this->user_id,
            'country' =>  $this->NullCheck($this->country_data, 'name'), 
            'company_name'=>$this->company_name,
            'city' => $this->company_city,
            'state' => $this->state,
            'created_at'=>$this->created_at,
            'website'=>$this->website,
            'looking_for_pro'=>$this->looking_for_pro,
            'looking_for_gw'=>$this->looking_for_gw,
            'looking_for_dm' => $this->looking_for_dm,
            'company_logo' => $this->company_logo,
            
        ];


        
    }

    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
    }
}