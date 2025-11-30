<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
          'title' => $this->title,
          'message'=>$this->message,
          'status'=>$this->status,
          'seen'=>$this->read_status,
          'humanDate'=>$this->created_at->diffForHumans(),
          'date'=>$this->created_at,
        ];
    }
}