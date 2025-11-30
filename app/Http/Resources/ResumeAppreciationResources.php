<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeAppreciationResources extends JsonResource
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
            'name' => $this->name,
            'organization' => $this->organization,
            'month' => $this->month,
            'year' => $this->year,
            'delete_status' => $this->delete_status,
            'status' => $this->status,



            
        ];
    }
}