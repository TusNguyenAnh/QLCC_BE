<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // LỚP 2 - GIAO DỊCH THỰC TẾ (IMMUTABLE)
        return [
            'id' => $this->id,
            'type' => $this->type,  // revenue | expense
            'related_id' => $this->related_id,
            'amount' => $this->amount,
            'final_amount' => $this->final_amount,  // amount + SUM(adjustments)
            'transaction_date' => $this->transaction_date,
            'payment_method' => $this->payment_method,
            'description' => $this->description,

            // 🆕 Thông tin phiếu thu/chi và quỹ
            'voucher_number' => $this->voucher_number,
            'fund_type' => $this->fund_type,
            'building_id' => $this->building_id,

            // 🆕 Thông tin người liên quan
            'payer_name' => $this->payer_name,
            'receiver_name' => $this->receiver_name,
            'contact_info' => $this->contact_info,

            // 🆕 Thông tin ngân hàng
            'bank_transaction_id' => $this->bank_transaction_id,
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,

            'created_by' => $this->created_by,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'adjustments' => AdjustmentTransactionResource::collection($this->whenLoaded('adjustments')),
            'balance' => $this->balance,
        ];
    }
}
