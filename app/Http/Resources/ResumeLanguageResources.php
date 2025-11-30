<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeLanguageResources extends JsonResource
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
            // 'user_id' => $this->user_id,
            'language' => $this->language,
            'speaking' => $this->speaking,
            'reading' => $this->reading,
            'writing' => $this->writing,
            'delete_status' => $this->delete_status,



            
        ];
    }


    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
     }
}