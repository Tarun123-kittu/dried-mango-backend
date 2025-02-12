<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'mobile' => $this->mobile,
            'gender' => $this->gender,
            'address' => $this->address,
            'status' => $this->status ? 'Active' : 'Inactive',
            'role_id' => $this->role_id,
            'role_name' => optional($this->role)->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
