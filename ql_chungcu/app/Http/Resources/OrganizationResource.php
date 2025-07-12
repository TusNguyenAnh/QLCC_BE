<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'org_code' => $this->org_code,
            'org_name' => $this->org_name,
            'parent_org_id' => $this->parent_org_id,
            'description' => $this->description,
            'status' => $this->status,
            'level' => $this->level,
            'child' => OrganizationResource::collection($this->whenLoaded('children')),
        ];
    }
}
