<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tithe\StoreRequest;
use App\Models\Tithe;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTransactionDetail;
use App\Services\ExceptionHandlerService;
use App\Services\PaymayaService;
use App\Services\TitheService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TitheController extends Controller
{
    private $paymayaService;
    public function __construct(PaymayaService $paymayaService)
    {
        $this->paymayaService = $paymayaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $endPoint = 'list';

        if ($request->ajax()) {
            $tithes = Tithe::query();

            if (auth()->user()->hasRole('admin') === false) {
                $tithes->whereHas('user', function ($q) {
                    $q->where('id', auth()->user()->id);
                });
            }

            return DataTables::of($tithes)
                ->addColumn('user', function ($row) {
                    return ($row->user->first_name ?? ' ') . ' ' . ($row->user->last_name ?? ' ');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->addColumn('section', function ($row) {
                    return $row->user->section->name ?? 'No Section Found';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('M d, Y');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'paid') {
                        return "<div class='badge bg-success'>Paid</div>";
                    }

                    if ($row->status == 'unpaid') {
                        return "<div class='badge bg-warning'>Unpaid</div>";
                    }

                })
                ->addColumn('actions', function ($tithe) {
                    $actions = "<div class='hstack gap-2'>
                        <a href='" . route('tithes.show', ['tithe' => $tithe->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='View'><i class='ri-eye-fill align-bottom'></i></a>
                    </div>";

                    return $actions;
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        return view("pages.tithes.list", compact("endPoint"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $endPoint = 'create';
        return view('pages.tithes.create', compact('endPoint'));
    }

    /**
     * Store a newly created resource in database.
     */
    public function store(StoreRequest $request)
    {
        try {
            $titheService = new TitheService;
            $result = $titheService->store($request);

            if ($result['payment_url']) {
                return redirect($result['payment_url']);
            }

            return redirect()->route('tithes.index')->withSuccess('Tithe Added Successfully!');

        } catch (Exception $exception) {
            $exceptionHandler = new ExceptionHandlerService();
            return $exceptionHandler->handler($request, $exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $endPoint = "Details";
        $tithe = Tithe::findOrFail($id);
        return view('pages.tithes.show', compact('tithe', 'endPoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tithe = Tithe::find($id);
        $tithe->delete();

        return response()->json([
            'status' => true,
            'message' => 'Tithe deleted successfully',
        ]);
    }

    public function userMonthlyTithes(Request $request)
    {
        $user_mfc_id_number = auth()->user()->mfc_id_number;

        $tithes = Tithe::select("for_the_month_of", DB::raw("SUM(amount) as total"), DB::raw("MAX(mfc_user_id) as mfc_user_id"), DB::raw("MAX(amount) as amount"), DB::raw("MAX(status) as status"))
            ->where("mfc_user_id", $user_mfc_id_number)
            ->where("status", "paid")
            ->groupBy("for_the_month_of")
            ->get();

        return response()->json([
            'status' => 'success',
            'tithes' => $tithes,
        ]);
    }
}
