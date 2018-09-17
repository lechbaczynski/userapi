<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FieldResource;
use App\Http\Resources\FieldResourceCollection;

class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        
        return [
            'name' => $this->name,
            'email' => $this->email,
            'state' => $this->state,
            'fields' => FieldResource::collection($this->fields),
        ];
    }
}
