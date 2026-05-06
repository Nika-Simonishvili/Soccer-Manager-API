<?php

namespace App\DTOs;

class RegisterDTO
{
    public function __construct(
        public string $fullName,
        public string $email,
        public string $password
    ) {}
}
