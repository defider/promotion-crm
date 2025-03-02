<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'building_id' => $this->building_id,
            'number' => $this->number,
            'reaction_id' => $this->reaction_id,
            'reaction_time' => $this->reaction_time,
        ];
    }
}
