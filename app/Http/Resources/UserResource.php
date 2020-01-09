<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'contract_start_date' => Carbon::parse($this->contract_start_date)->format('d.m.Y.'),
            'contract_end_date' => Carbon::parse($this->contract_end_date)->format('d.m.Y.'),
            'type' => ucfirst($this->type),
            'verified' => ($this->verified ? 'Yes' : 'No')
        ];
    }
}
