<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\OTP\VerifyRequest;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\ExceptionHandlerService;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $authService = new AuthService();
            $user = $authService->login($credentials);

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
        try {
            $data = $request->validated();
            $authService = new AuthService();

            $user = $authService->register($request);

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

    public function resendOTP(Request $request)
    {
        try {
            $user = Auth::user();
            $user->sendOTPVerificationNotification();

            return response()->json([
                'status' => 'success',
                'message' => 'Resend Successfully',
            ]);

        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService;
            return $exceptionHandler->handler($request, $exception);
        }
    }

    public function verifyOTP(VerifyRequest $request)
    {
        try {
            $authService = new AuthService();
            $user = $authService->otpVerify($request);

            return response()->json([
                'status' => 'success',
                'message' => 'Verified Successfully',
                'user' => UserResource::make($user->load('user_details', 'section')),
            ]);

        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService;
            return $exceptionHandler->handler($request, $exception);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout Successfully',
            ]);

        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService;
            return $exceptionHandler->handler($request, $exception);
        }
    }
}
