<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
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
            'building_id' => $this->building_id,
            'apt_number' => $this->apt_number,
            'apt_area' => $this->apt_area,
            'apt_type' => $this->apt_type,
            'description' => $this->description,
            'floor' => $this->floor,
            'status' => $this->status,
        ];
    }
}
