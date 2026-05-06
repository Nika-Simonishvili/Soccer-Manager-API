<?php

namespace App\Events;

use App\Models\Player\Player;
use App\Models\Team;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerSold
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Team $sellerTeam,
        public Team $buyerTeam,
        public Player $player,
        public float $price
    ) {
        //
    }
}
