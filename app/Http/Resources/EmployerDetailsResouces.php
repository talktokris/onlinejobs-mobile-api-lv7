<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerDetailsResouces extends JsonResource
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
               'logo'=>$this->company_logo,
               'company_name'=>$this->company_name,
               'company_address'=>$this->company_address,
               'company_country'=>$this->company_country,
               'company_country_data'=>$this->company_country_data,
               'company_city'=>$this->company_city,
               'state'=>$this->state,
               'company_email'=>$this->company_email,
               'company_phone'=>$this->company_phone,
               'website'=>$this->website,
               

       ];
       
    }
}