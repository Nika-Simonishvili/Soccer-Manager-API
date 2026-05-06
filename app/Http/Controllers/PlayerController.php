<?php

namespace App\Http\Controllers;

use App\Http\Requests\PutPlayerOnMarketPlaceRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Marketplace;
use App\Models\Player\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    public function update(Player $player, UpdatePlayerRequest $request): JsonResponse
    {
        $this->authorize('update', $player);

        $player->update($request->validated());

        return Response::success(message: __('messages.player.updated'), data: [
            'player' => PlayerResource::make($player),
        ]);
    }

    public function putPlayerOnMarketplace(Player $player, PutPlayerOnMarketPlaceRequest $request): JsonResponse
    {
        $this->authorize('putOnMarketplace', $player);

        $dto = $request->toDTO();

        Marketplace::create([
            'player_id' => $dto->playerId,
            'price' => $dto->price,
        ]);

        return Response::success(message: __('messages.player.put_on_marketplace'), data: [
            'player' => PlayerResource::make($player),
        ]);
    }
}
