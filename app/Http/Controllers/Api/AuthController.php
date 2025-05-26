<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\ExceptionHandlerService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $authService = new AuthService();
            $user = $authService->login($request, $credentials);

            return response()->json([
                'status' => 'success',
                'user' => UserResource::make($user->load('user_details', 'section')),
                'token' => $user->createToken("user-token-{$user->id}")->plainTextToken,
            ]);
        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService;
            return $exceptionHandler->handler($request, $exception);
        }
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $authService = new AuthService();

        $user = $authService->register($request);

    }
}
