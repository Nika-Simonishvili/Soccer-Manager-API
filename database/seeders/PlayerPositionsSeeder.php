<?php

namespace Database\Seeders;

use App\Models\Player\PlayerPosition;
use Illuminate\Database\Seeder;

class PlayerPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Goalkeeper'],
            ['name' => 'Defender'],
            ['name' => 'Midfielder'],
            ['name' => 'Attacker'],
        ];

        PlayerPosition::insert($data);
    }
}
