<?php

use App\Enums\PlayerPositionsEnum;
use App\Models\Country;
use App\Models\Player\Player;
use App\Models\Team;
use App\Models\User;

test('team owner can update player first name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $this->actingAs($user)
        ->putJson("/api/player/{$player->id}", ['first_name' => 'Updated'])
        ->assertOk()
        ->assertJsonPath('data.player.first_name', 'Updated');

    expect($player->fresh()->first_name)->toBe('Updated');
});

test('team owner can update player last name and country', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::DEFENDER->value]);
    $country = Country::first();

    $this->actingAs($user)
        ->putJson("/api/player/{$player->id}", [
            'last_name' => 'Smith',
            'country_id' => $country->id,
        ])
        ->assertOk();

    expect($player->fresh()->last_name)->toBe('Smith')
        ->and($player->fresh()->country_id)->toBe($country->id);
});

test('user cannot update a player from another team', function () {
    $user = User::factory()->create();
    Team::factory()->for($user)->create();

    $other = User::factory()->create();
    $otherTeam = Team::factory()->for($other)->create();
    $player = Player::factory()->for($otherTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $this->actingAs($user)
        ->putJson("/api/player/{$player->id}", ['first_name' => 'Hack'])
        ->assertForbidden();
});

test('unauthenticated user cannot update player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $this->putJson("/api/player/{$player->id}", ['first_name' => 'Hack'])
        ->assertUnauthorized();
});

test('team owner can put player on marketplace', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::MIDFIELDER->value]);

    $this->actingAs($user)
        ->postJson("/api/player/{$player->id}/marketplace", ['price' => '1500000.00'])
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('marketplace', [
        'player_id' => $player->id,
        'price' => '1500000.00',
    ]);
});

test('marketplace listing requires a price', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::ATTACKER->value]);

    $this->actingAs($user)
        ->postJson("/api/player/{$player->id}/marketplace", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['price']);
});

test('user cannot list another teams player on marketplace', function () {
    $user = User::factory()->create();
    Team::factory()->for($user)->create();

    $other = User::factory()->create();
    $otherTeam = Team::factory()->for($other)->create();
    $player = Player::factory()->for($otherTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $this->actingAs($user)
        ->postJson("/api/player/{$player->id}/marketplace", ['price' => '1500000.00'])
        ->assertForbidden();
});

test('unauthenticated user cannot list player on marketplace', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $this->postJson("/api/player/{$player->id}/marketplace", ['price' => '1500000.00'])
        ->assertUnauthorized();
});
