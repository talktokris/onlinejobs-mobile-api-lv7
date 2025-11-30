<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobBookmarkResources extends JsonResource
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
                'job_id' => $this->job_id,
                'user_id'=>$this->user_id,
                'delete_status'=>$this->delete_status,

        ];
    }
}