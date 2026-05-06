<?php

namespace App\Listeners;

use App\Events\PlayerSold;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTeamValues implements ShouldQueue
{
    public function handle(PlayerSold $event): void
    {
        $event->sellerTeam->value = $event->sellerTeam->players()->sum('value');
        $event->sellerTeam->save();

        $event->buyerTeam->value = $event->buyerTeam->players()->sum('value');
        $event->buyerTeam->save();
    }
}
