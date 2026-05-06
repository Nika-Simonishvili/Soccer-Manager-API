<?php

namespace App\Policies;

use App\Models\Player\Player;
use App\Models\User;

class PlayerPolicy
{
    public function update(User $user, Player $player)
    {
        return $user->team->id === $player->team->id;
    }

    public function putOnMarketplace(User $user, Player $player)
    {
        return $user->team->id === $player->team->id;
    }
}
