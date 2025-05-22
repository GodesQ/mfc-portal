<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {

    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $authService = new AuthService();

        $user = $authService->register($request);

    }
}
