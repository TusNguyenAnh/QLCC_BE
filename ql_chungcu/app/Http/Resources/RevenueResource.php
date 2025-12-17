<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueResource extends JsonResource
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
            'apartment_id' => $this->apartment_id,
            'apartment' => new ApartmentResource($this->apartment),
            'year' => $this->year,
            'month' => $this->month,
            'original_amount' => $this->original_amount,  // Nghĩa vụ gốc
            'amount_paid' => $this->amount_paid,  // Cache tổng đã thu
            'remaining' => max(0, $this->original_amount - $this->amount_paid),  // Còn nợ
            'status' => $this->status,
            'description' => $this->description,
        ];
    }
}
