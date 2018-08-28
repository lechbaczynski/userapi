<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'title' => $this->title,
            'type' => $this->type,
        ];
        
        if ($this->value !== null) {
            $data['value'] = $this->value;
            
        }
        
        return $data;
    }
}
