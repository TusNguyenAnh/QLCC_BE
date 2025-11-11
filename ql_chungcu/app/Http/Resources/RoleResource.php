<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'role_name' => $this->role_name,
            'description' => $this->description,
            'status' => $this->status,
            'complex_id' => $this->complex_id,
            'total_permission' => $this->permissions->count(),
            'total_user' => $this->users->count(),
            'permission' => $this->permissions->pluck('id')->toArray()
        ];
    }
}
