<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailsResources extends JsonResource
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
                    'type' => $this->type,
                    'point' => $this->point_details,
                    'delete_status' => $this->delete_status,
                    // 'worker_type' => $this->worker_type,
                ];


        }
}