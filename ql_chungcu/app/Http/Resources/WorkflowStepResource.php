<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkflowStepResource extends JsonResource
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
            'org_level' => $this->org_level,
            'step_order' => $this->step_order,
            'description' => $this->description,
            'status' => $this->status,

            //position theo workflowStepId
            'position' => $this->approvers->map(fn ($approver) => [
                'id' => $approver->role->id,
                'role_name' => $approver->role->role_name,
            ])->values(),
        ];
    }
}
