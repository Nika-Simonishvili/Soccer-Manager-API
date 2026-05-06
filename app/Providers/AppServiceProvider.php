<?php

namespace App\Providers;

use App\Contracts\AuthServiceContract;
use App\Services\AuthService;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            abstract: AuthServiceContract::class,
            concrete: AuthService::class,
        );
    }

    public function boot(): void
    {
        Response::macro('success', function ($status = 200, ?string $message = '', $data = null) {
            return response()->json([
                'success' => true,
                'statusCode' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function ($status = 400, ?string $message = '', $data = null) {
            return response()->json([
                'success' => false,
                'statusCode' => $status,
                'message' => $message,
                'data' => $data,
            ], $status);
        });
    }
}
