<?php

namespace App\Http\Controllers;

use App\Contracts\AuthServiceContract;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthServiceContract $authService): JsonResponse
    {
        $user = $authService->register($request->toDTO());

        return Response::success(message: 'User registered successfully', data: [
            'user' => UserResource::make($user),
        ]);
    }

    public function login(LoginRequest $request, AuthServiceContract $authService): JsonResponse
    {
        $token = $authService->login($request->toDTO());

        return Response::success(message: 'User logged in successfully', data: [
            'token' => $token,
        ]);
    }

    public function logout(AuthServiceContract $authService): JsonResponse
    {
        $authService->logout();

        return Response::success(message: 'User logged out successfully');
    }
}
