<?php

namespace App\Listeners;

use App\Events\PlayerSold;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreasePlayerMarketValue implements ShouldQueue
{
    public function handle(PlayerSold $event): void
    {
        $percentage = rand(10, 100) / 100;
        $event->player->value += $event->player->value * $percentage;
        $event->player->save();

        $event->buyerTeam->value = $event->buyerTeam->players()->sum('value');
        $event->buyerTeam->save();
    }
}
