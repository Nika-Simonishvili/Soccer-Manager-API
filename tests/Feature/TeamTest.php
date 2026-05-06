<?php

use App\Enums\PlayerPositionsEnum;
use App\Models\Country;
use App\Models\Player\Player;
use App\Models\Team;
use App\Models\User;

test('authenticated user can view their team', function () {
    $user = User::factory()->create();
    Team::factory()->for($user)->create();

    $this->actingAs($user)
        ->getJson('/api/team')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'data' => [
                'team' => ['name', 'value', 'country', 'players'],
            ],
        ]);
});

test('team response includes players with position and country', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();

    Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);
    Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::DEFENDER->value]);

    $this->actingAs($user)
        ->getJson('/api/team')
        ->assertOk()
        ->assertJsonCount(2, 'data.team.players');
});

test('unauthenticated user cannot view team', function () {
    $this->getJson('/api/team')->assertUnauthorized();
});

test('user can update team name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();

    $this->actingAs($user)
        ->putJson('/api/team', ['name' => 'New Name'])
        ->assertOk()
        ->assertJsonPath('success', true);

    expect($team->fresh()->name)->toBe('New Name');
});

test('user can update team country', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create();
    $country = Country::first();

    $this->actingAs($user)
        ->putJson('/api/team', ['country_id' => $country->id])
        ->assertOk();

    expect($team->fresh()->country_id)->toBe($country->id);
});

test('update rejects invalid country id', function () {
    $user = User::factory()->create();
    Team::factory()->for($user)->create();

    $this->actingAs($user)
        ->putJson('/api/team', ['country_id' => 99999])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['country_id']);
});

test('unauthenticated user cannot update team', function () {
    $this->putJson('/api/team', ['name' => 'New Name'])->assertUnauthorized();
});
