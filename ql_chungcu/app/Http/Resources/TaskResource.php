<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'tasktype_id' => $this->tasktype_id,
            'complex_id' => $this->complex_id,
            'current_step_id' => $this->current_step_id,
            'current_org_id' => $this->current_org_id,
            'user_id' => $this->user_id,
            'task_name' => $this->task_name,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
