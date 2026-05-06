<?php

namespace App\Http\Controllers;

use App\Http\Resources\MarketPlaceResource;
use App\Models\Marketplace;
use App\Models\Team;
use App\Services\MarketPlaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MarketPlaceController extends Controller
{
    public function __construct(
        private readonly MarketPlaceService $marketplaceService
    ) {}

    public function index(): JsonResponse
    {
        $data = $this->marketplaceService->index();

        return Response::success(message: 'Players on marketplace retrieved successfully',
            data: $data->through(fn ($item) => MarketPlaceResource::make($item)));
    }

    public function buyPlayer(Marketplace $marketplace): JsonResponse
    {
        $buyerTeam = Team::whereBelongsTo(auth()->user())->first();

        $this->marketplaceService->buyPlayer($marketplace, $buyerTeam);

        return Response::success(message: 'Player bought successfully');
    }
}
