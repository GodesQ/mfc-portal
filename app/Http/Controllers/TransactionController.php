<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Models\EventRegistration;
use App\Models\Tithe;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request) {
        if($request->ajax()) {
            $transactions = Transaction::query();
            return DataTables::of($transactions)
                    ->editColumn('total_amount', function($row) {
                        return "₱ " . number_format($row->total_amount,2);
                    })
                    ->editColumn("payment_type", function($row) {
                        if($row->payment_type == 'event_registration') {
                            return "<div class='badge bg-primary'>event_registration</div>";
                        } else {
                            return "<div class='badge bg-secondary'>$row->payment_type</div>";
                        }
                    })
                    ->editColumn("status", function ($row) {
                        if ($row->status == 'paid') {
                            return "<div class='badge bg-success'>paid</div>";
                        } else {
                            return "<div class='badge bg-warning'>$row->status</div>";
                        }
                    })
                    ->addColumn('actions', function ($data) {
                        $actions = "<div class='hstack gap-2'>
                            <a href='" . route('transactions.show', ['transaction' => $data->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='View'>
                                <i class='ri-eye-fill align-bottom'></i>
                            </a>
                        </div>";
    
                        return $actions;
                    })
                    ->rawColumns(["status", "actions", "payment_type"])
                    ->make(true);
        }

        return view('pages.transactions.index');
    }

    public function show(Request $request, $id) {
        $transaction = Transaction::where('id', $id)->first();

        $items = [];

            if($transaction->payment_type == PaymentType::EVENT_REGISTRATION) {
                $items = EventRegistration::where('transaction_id', $transaction->id)->get()->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => ($row->user->first_name ?? " ") . ' ' . ($row->user->last_name ?? " "),
                        'mfc_id_number' => ($row->user->mfc_id_number ?? " "),
                        'payment_type' => "Event Registration",
                        'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                        'amount' => $row->amount,
                    ];
                })->toArray();
            }

            if($transaction->payment_type == PaymentType::TITHE) {
                $items = Tithe::where('transaction_id', $transaction->id)->get()->map(function ($row) {
                    return [
                        'id' => $row->id,
                        'name' => ($row->user->first_name ?? " ") . ' ' . ($row->user->last_name ?? " "),
                        'mfc_id_number' => ($row->user->mfc_id_number ?? " "),
                        'payment_type' => "Tithe",
                        'date' => Carbon::parse($row->created_at)->format('M d, Y'),
                        'amount' => $row->amount,
                    ];
                });
            }

        return view('pages.transactions.show', compact('transaction', 'items'));

    }
}
