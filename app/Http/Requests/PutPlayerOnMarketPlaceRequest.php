<?php

namespace App\Http\Requests;

use App\DTOs\PutPlayerOnMarketPlaceDTO;
use Illuminate\Foundation\Http\FormRequest;

class PutPlayerOnMarketPlaceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'price' => 'required|decimal:2',
        ];
    }

    public function toDTO(): PutPlayerOnMarketPlaceDTO
    {
        return new PutPlayerOnMarketPlaceDTO(
            playerId: $this->route('player')->id,
            price: $this->input('price'),
        );
    }
}
