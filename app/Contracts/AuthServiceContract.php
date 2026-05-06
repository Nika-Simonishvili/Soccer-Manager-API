<?php

namespace App\Contracts;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Models\User;

interface AuthServiceContract
{
    public function register(RegisterDTO $data): User;

    public function login(LoginDTO $data): string;

    public function logout(): void;
}
