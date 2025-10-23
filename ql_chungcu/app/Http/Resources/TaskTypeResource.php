<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskTypeResource extends JsonResource
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
            'workflow_id' => $this->workflow_id,
            'description' => $this->description,
            'type_name' => $this->type_name,
            'status' => $this->status,
            'priority' => $this->priority
        ];
    }
}
