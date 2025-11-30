<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeSkillResources extends JsonResource
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
            'skill_id' => $this->skill_id,
            'level_id' => $this->level_id,
            'experience' => $this->year,
            'delete_status' => $this->delete_status,
            'status' => $this->status,
            // 'skill_name'=> $this->skillInfo->name,
            // 'skill_type'=>$this->skillInfo->type,
            // 'skill_level' => $this->levelInfo->name,
            'skill_name' =>  $this->NullCheck($this->skillInfo, 'name'),
            'skill_type' =>  $this->NullCheck($this->skillInfo, 'type'),
            'skill_level' =>  $this->NullCheck($this->levelInfo, 'name'),

            
        ];



    }





    public function NullCheck($data, $fillName)
    {
         if($data==null){  return $data ;} else { return  $data->$fillName;}
 
     }

     
}