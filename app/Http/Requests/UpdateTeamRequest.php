<?php

namespace App\Http\Requests;

use App\DTOs\UpdateTeamDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'country_id' => 'sometimes|exists:countries,id',
        ];
    }

    public function toDTO(): UpdateTeamDTO
    {
        return new UpdateTeamDTO(
            name: $this->string('name') ?? null,
            country_id: $this->input('country_id') ?? null
        );
    }
}
