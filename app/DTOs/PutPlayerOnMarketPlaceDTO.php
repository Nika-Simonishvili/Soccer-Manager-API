<?php

namespace App\DTOs;

class PutPlayerOnMarketPlaceDTO
{
    public function __construct(
        public int $playerId,
        public float $price,
    ) {}
}
