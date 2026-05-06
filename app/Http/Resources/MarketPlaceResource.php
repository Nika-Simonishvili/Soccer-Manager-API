<?php

namespace App\Http\Resources;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketPlaceResource extends JsonResource
{
    /** @var Marketplace */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'player' => PlayerResource::make($this->whenLoaded('player')),
        ];
    }
}
