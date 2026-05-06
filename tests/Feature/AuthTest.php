<?php

use App\Models\User;

test('user can register', function () {
    $this->postJson('/api/register', [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.email', 'john@example.com');
});

test('registration creates a team with 20 players', function () {
    $this->postJson('/api/register', [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'john@example.com')->with('team.players')->first();

    expect($user->team)->not->toBeNull()
        ->and($user->team->players)->toHaveCount(20)
        ->and((float) $user->team->budget)->toBe(5_000_000.0)
        ->and((float) $user->team->value)->toBe(20_000_000.0);
});

test('registration validates required fields', function () {
    $this->postJson('/api/register', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['full_name', 'email', 'password']);
});

test('registration fails with duplicate email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/api/register', [
        'full_name' => 'John Doe',
        'email' => 'taken@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('registration fails when password confirmation does not match', function () {
    $this->postJson('/api/register', [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different',
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('user can login and receives token', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data' => ['token']]);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ])->assertUnauthorized();
});

test('login fails with non-existent email', function () {
    $this->postJson('/api/login', [
        'email' => 'nobody@example.com',
        'password' => 'password123',
    ])->assertUnauthorized();
});

test('login validates required fields', function () {
    $this->postJson('/api/login', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertOk()
        ->assertJsonPath('success', true);
});

test('unauthenticated user cannot logout', function () {
    $this->postJson('/api/logout')->assertUnauthorized();
});
