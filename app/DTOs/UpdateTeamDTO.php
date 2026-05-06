<?php

namespace App\DTOs;

class UpdateTeamDTO
{
    public function __construct(
        public ?string $name = null,
        public ?int $country_id = null
    ) {}
}
