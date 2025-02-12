<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'company_name' => $this->company_name,
            'gender' => $this->gender,
            'address' => $this->address,
            'status' => $this->status ? 'Active' : 'Inactive',
            'date_of_joining' => $this->date_of_joining,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
