<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JobListingResources;

class JobBookmarkListResources extends JsonResource
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
            'job_id' => $this->id,
            'user_id'=>$this->company_name,
            'delete_status'=>$this->delete_status,
            'job_details'=>  JobListingResources::collection($this->job_details),
        ];
    }
}