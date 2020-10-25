<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'projectId' => $this->projectId,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount
        ];
    }
}
