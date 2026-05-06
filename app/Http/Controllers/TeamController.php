<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function show(): JsonResponse
    {
        $team = Team::with(['players.country', 'country', 'players.position'])
            ->whereBelongsTo(auth()->user())
            ->first();

        return Response::success(message: 'Team retrieved successfully', data: [
            'team' => TeamResource::make($team),
        ]);
    }

    public function update(UpdateTeamRequest $request): JsonResponse
    {
        $team = Team::whereBelongsTo(auth()->user())->firstOrFail();

        $this->authorize('update', $team);

        $team->update($request->validated());

        return Response::success(message: 'Team updated successfully', data: TeamResource::make($team));
    }
}
