<?php

namespace App\Enums;

enum PlayerPositionsEnum: int
{
    case GOALKEEPER = 1;
    case DEFENDER = 2;
    case MIDFIELDER = 3;
    case ATTACKER = 4;
}
