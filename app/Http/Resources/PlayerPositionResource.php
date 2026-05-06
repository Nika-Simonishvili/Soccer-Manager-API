<?php

namespace App\Http\Resources;

use App\Models\Player\PlayerPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerPositionResource extends JsonResource
{
    /** @var PlayerPosition */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
