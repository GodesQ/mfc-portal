<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tithe\StoreRequest;
use App\Http\Resources\TitheResource;
use App\Models\Tithe;
use App\Services\ExceptionHandlerService;
use App\Services\TitheService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TitheController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tithe::query();

        // Date range filter
        if ($request->has('date_start') && $request->has('date_end')) {
            $query->whereBetween('created_at', [
                $request->date_start,
                $request->date_end
            ]);
        } elseif ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Month range filter
        if ($request->has('month_start') && $request->has('month_end')) {
            $start = Carbon::parse($request->month_start)->startOfMonth();
            $end = Carbon::parse($request->month_end)->startOfMonth();

            $months = [];
            while ($start <= $end) {
                $months[] = $start->format('F'); // "F" gives full month name like "May"
                $start->addMonth();
            }

            $query->whereIn('for_the_month_of', $months);

        } elseif ($request->has('month')) {
            $monthName = Carbon::parse($request->month)->format('F');
            $query->where('for_the_month_of', $monthName);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_amount') && $request->filled('max_amount')) {
            $minAmount = (int) $request->min_amount;
            $maxAmount = (int) $request->max_amount;
            $query->whereBetween('amount', [$minAmount, $maxAmount]);
        }

        $query->where('mfc_user_id', $user->mfc_id_number);

        return response()->json([
            'status' => 'success',
            'tithes' => TitheResource::collection($query->get()),
        ]);
    }

    public function userTithes(Request $request, $user_id)
    {
        $user = Auth::user();
        $titheService = new TitheService;
        $tithes = $titheService->getUserTithes($request, $user);

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
