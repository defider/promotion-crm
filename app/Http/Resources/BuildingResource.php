<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'region' => $this->region->title,
            'postcode' => $this->postcode,
            'district' => $this->district,
            'locality' => $this->locality,
            'street' => $this->street,
            'number' => $this->number,
        ];
    }
}
