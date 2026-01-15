<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'org_id' => $this->org_id,
            'gender' => $this->gender,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'relationship' => $this->relationship,
            'cccd' => $this->cccd,
            'apt_id' => $this->apt_id,
            'status' => $this->status,
            'position' => $this->position,
        ];
    }
}
