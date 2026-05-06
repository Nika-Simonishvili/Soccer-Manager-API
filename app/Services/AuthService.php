<?php

namespace App\Services;

use App\Contracts\AuthServiceContract;
use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

final readonly class AuthService implements AuthServiceContract
{
    public function register(RegisterDTO $data): User
    {
        $user = User::create(attributes: [
            'full_name' => $data->fullName,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        event(new Registered($user));

        return $user;
    }

    public function login(LoginDTO $data): string
    {
        $credentials = [
            'email' => $data->email,
            'password' => $data->password,
        ];

        if (! auth()->attempt($credentials)) {
            abort(401, 'Invalid credentials');
        }

        return auth()->user()->createToken(name: 'auth_token')->plainTextToken;
    }

    public function logout(): void
    {
        auth()->user()->currentAccessToken()->delete();
    }
}
