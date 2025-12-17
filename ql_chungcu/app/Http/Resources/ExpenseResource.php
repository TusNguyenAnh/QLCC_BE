<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'title' => $this->title,
            'category' => $this->category,
            'original_amount' => $this->original_amount,  // Nghĩa vụ gốc
            'amount_paid' => $this->amount_paid,  // Cache tổng đã chi
            'remaining' => max(0, $this->original_amount - $this->amount_paid),  // Còn lại
            'status' => $this->status,
            'vendor' => $this->vendor,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'approved_by' => $this->approved_by,
            'approver' => new UserResource($this->whenLoaded('approver')),
            'approved_at' => $this->approved_at,
        ];
    }
}
