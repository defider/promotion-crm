<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'building_id' => $this->building_id,
            'leaflet_id' => $this->leaflet_id,
            'began_at' => $this->began_at,
            'ended_at' => $this->ended_at,
        ];
    }
}
