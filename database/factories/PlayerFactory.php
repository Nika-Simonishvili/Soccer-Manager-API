<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'age' => fake()->numberBetween(18, 40),
            'value' => 1_000_000,
            'country_id' => Country::inRandomOrder()->value('id'),
        ];
    }
}
