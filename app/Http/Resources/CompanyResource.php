<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'logo' => $this->logo,
            'sub_logo' => $this->subLogo,
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'contact' => $this->contact,
            'province' => $this->province,
            'district' => $this->district,
            'municipality' => $this->municipality,
            'address' => $this->address,
            'website' => $this->website,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'expiry_date' => $this->expiry_date,
        ];
    }
}
