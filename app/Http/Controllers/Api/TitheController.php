<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tithe\StoreRequest;
use App\Http\Resources\TitheResource;
use App\Services\ExceptionHandlerService;
use App\Services\TitheService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TitheController extends Controller
{
    public function index()
    {
        
    }

    public function userTithes(Request $request, $user_id)
    {
        $user = Auth::user();
        $titheService = new TitheService;
        $tithes = $titheService->getUserTithes($user);

        return response()->json([
            'status' => 'success',
            'tithes' => TitheResource::collection($tithes),
        ]);
    }

    public function store(StoreRequest $request)
    {
        try {
            $titheService = new TitheService;
            $result = $titheService->store($request);

            return response()->json([
                'status' => 'success',
                'tithe' => TitheResource::make($result['tithe']),
                'payment_link' => $result['payment_url'],
            ]);

        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService();
            return $exceptionHandler->handler($request, $exception);
        }
    }
}
