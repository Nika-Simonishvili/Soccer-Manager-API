<?php

namespace App\Http\Requests;

use App\DTOs\RegisterDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function toDTO(): RegisterDTO
    {
        return new RegisterDTO(
            fullName: $this->string('full_name'),
            email: $this->string('email'),
            password: $this->string('password')
        );
    }
}
