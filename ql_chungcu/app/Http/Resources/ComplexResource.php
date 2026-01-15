<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplexResource extends JsonResource
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
            'complex_name' => $this->complex_name,
            'address' => $this->address,
            'total_building' => $this->total_building,
            'total_apartment' => $this->total_apartment,
            'name_contact' => $this->name_contact,
            'phone_contact' => $this->phone_contact,
            'email_contact' => $this->email_contact,
            'description' => $this->description,
            'financial_model' => $this->financial_model,
        ];
    }
}
