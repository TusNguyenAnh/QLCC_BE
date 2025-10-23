<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkflowResource extends JsonResource
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
            'complex_id' => $this->complex_id,
            'workflow_name' => $this->workflow_name,
            'status' => $this->status,
            'description' => $this->description,
            'workflow_step' => $this->workflowStep,
            'task_type' => TaskTypeResource::collection($this->taskType),
        ];
    }
}
