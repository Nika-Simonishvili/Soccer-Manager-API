<?php

namespace App\Http\Resources;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /** @var Team */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'players' => PlayerResource::collection($this->whenLoaded('players')),
        ];
    }
}
