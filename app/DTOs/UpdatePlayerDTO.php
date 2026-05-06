<?php

namespace App\DTOs;

class UpdatePlayerDTO
{
    public function __construct(
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?int $country_id = null
    ) {}
}
