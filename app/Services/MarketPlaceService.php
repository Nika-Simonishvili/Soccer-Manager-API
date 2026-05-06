<?php

namespace App\Services;

use App\Events\PlayerSold;
use App\Models\Marketplace;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final readonly class MarketPlaceService
{
    public function index(): LengthAwarePaginator
    {
        return Marketplace::with(['player', 'player.country', 'player.team'])->paginate();
    }

    public function buyPlayer(Marketplace $marketplace, Team $buyerTeam): void
    {
        $marketplace->loadMissing(['player', 'player.team']);

        $sellerTeam = $marketplace->player->team;
        $player = $marketplace->player;

        if ($sellerTeam->id === $buyerTeam->id) {
            abort(code: 400, message: 'You cannot buy your own player');
        }

        DB::transaction(function () use ($marketplace, $buyerTeam, $sellerTeam, $player) {
            $ids = collect([$buyerTeam->id, $sellerTeam->id])->sort()->values()->all();
            $teams = Team::lockForUpdate()->whereIn('id', $ids)->get()->keyBy('id');

            $buyerTeam = $teams[$buyerTeam->id];
            $sellerTeam = $teams[$sellerTeam->id];

            if ($buyerTeam->budget < $marketplace->price) {
                abort(code: 422, message: 'Insufficient budget');
            }

            $player->team()->associate($buyerTeam);
            $player->save();

            $buyerTeam->budget -= $marketplace->price;
            $sellerTeam->budget += $marketplace->price;

            $buyerTeam->save();
            $sellerTeam->save();

            event(new PlayerSold(
                sellerTeam: $sellerTeam,
                buyerTeam: $buyerTeam,
                player: $player,
                price: $marketplace->price
            ));

            $marketplace->delete();
        });

    }
}
