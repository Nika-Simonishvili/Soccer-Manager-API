<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'country_id' => Country::inRandomOrder()->value('id'),
            'value' => 0,
            'budget' => 5_000_000,
        ];
    }
}
