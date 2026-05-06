<?php

namespace App\Http\Requests;

use App\DTOs\UpdatePlayerDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'country_id' => 'sometimes|int|exists:countries,id',
        ];
    }

    public function toDTO(): UpdatePlayerDTO
    {
        return new UpdatePlayerDTO(
            first_name: $this->input('first_name'),
            last_name: $this->input('last_name'),
            country_id: $this->input('country_id'),
        );
    }
}
