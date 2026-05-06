<?php

use App\Enums\PlayerPositionsEnum;
use App\Models\Marketplace;
use App\Models\Player\Player;
use App\Models\Team;
use App\Models\User;

test('authenticated user can view marketplace listings', function () {
    $this->actingAs(User::factory()->create())
        ->getJson('/api/marketplace')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data']);
});

test('unauthenticated user cannot view marketplace', function () {
    $this->getJson('/api/marketplace')->assertUnauthorized();
});

test('marketplace only shows players currently listed', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create();
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    Marketplace::create(['player_id' => $player->id, 'price' => 1_000_000]);

    $buyer = User::factory()->create();
    Team::factory()->for($buyer)->create();

    $response = $this->actingAs($buyer)->getJson('/api/marketplace');

    $response->assertOk();
    expect($response->json('data.data'))->toHaveCount(1);
});

test('user can buy a player from another team', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create(['budget' => 5_000_000]);
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $buyer = User::factory()->create();
    $buyerTeam = Team::factory()->for($buyer)->create(['budget' => 5_000_000]);

    $this->actingAs($buyer)
        ->postJson("/api/marketplace/{$listing->id}/buy")
        ->assertOk()
        ->assertJsonPath('success', true);

    expect($player->fresh()->team_id)->toBe($buyerTeam->id);
});

test('buyer budget decreases and seller budget increases after transfer', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create(['budget' => 5_000_000]);
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $buyer = User::factory()->create();
    $buyerTeam = Team::factory()->for($buyer)->create(['budget' => 5_000_000]);

    $this->actingAs($buyer)->postJson("/api/marketplace/{$listing->id}/buy")->assertOk();

    expect((float) $buyerTeam->fresh()->budget)->toBe(4_500_000.0)
        ->and((float) $sellerTeam->fresh()->budget)->toBe(5_500_000.0);
});

test('player value increases between 10 and 100 percent after transfer', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create();
    $player = Player::factory()->for($sellerTeam)->create([
        'position_id' => PlayerPositionsEnum::GOALKEEPER->value,
        'value' => 1_000_000,
    ]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $buyer = User::factory()->create();
    Team::factory()->for($buyer)->create(['budget' => 5_000_000]);

    $this->actingAs($buyer)->postJson("/api/marketplace/{$listing->id}/buy")->assertOk();

    $newValue = (float) $player->fresh()->value;

    expect($newValue)->toBeGreaterThanOrEqual(1_100_000.0)
        ->and($newValue)->toBeLessThanOrEqual(2_000_000.0);
});

test('marketplace listing is removed after purchase', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create();
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $buyer = User::factory()->create();
    Team::factory()->for($buyer)->create(['budget' => 5_000_000]);

    $this->actingAs($buyer)->postJson("/api/marketplace/{$listing->id}/buy")->assertOk();

    expect(Marketplace::find($listing->id))->toBeNull();
});

test('user cannot buy their own player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->for($user)->create(['budget' => 5_000_000]);
    $player = Player::factory()->for($team)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $this->actingAs($user)
        ->postJson("/api/marketplace/{$listing->id}/buy")
        ->assertStatus(400);
});

test('user cannot buy a player with insufficient budget', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create();
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 10_000_000]);

    $buyer = User::factory()->create();
    Team::factory()->for($buyer)->create(['budget' => 1_000_000]);

    $this->actingAs($buyer)
        ->postJson("/api/marketplace/{$listing->id}/buy")
        ->assertStatus(422);
});

test('unauthenticated user cannot buy a player', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->for($seller)->create();
    $player = Player::factory()->for($sellerTeam)->create(['position_id' => PlayerPositionsEnum::GOALKEEPER->value]);

    $listing = Marketplace::create(['player_id' => $player->id, 'price' => 500_000]);

    $this->postJson("/api/marketplace/{$listing->id}/buy")->assertUnauthorized();
});
