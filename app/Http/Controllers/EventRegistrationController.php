<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Http\Requests\EventRegistration\StoreRequest;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventRegistration;
use App\Models\EventUserDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PaymayaService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EventRegistrationController extends Controller
{   
    public $paymayaService;
    public function __construct(PaymayaService $paymayaService) {
        $this->paymayaService = $paymayaService;
    }

    public function list(Request $request, Event $event) {

        if($request->ajax()) {
            $registrations = EventRegistration::with('user', 'event', 'transaction');

            return DataTables::of($registrations)
                ->addColumn('user', function ($row) {
                    return $row->user->first_name . ' ' . $row->user->last_name;
                })
                ->addColumn('event', function ($row) {
                    return $row->event->title;
                })
                ->editColumn('amount', function ($row) {
                    return "₱ " . number_format($row->amount, 2);
                })
                ->addColumn('status', function ($row) {
                    if($row->transaction->status == 'paid') {
                        return "<div class='badge bg-success'>{$row->transaction->status}</div>";
                    } else {
                        return "<div class='badge bg-warning'>{$row->transaction->status}</div>";
                    }
                })
                ->addColumn('attendance_status', function ($row) {
                    $event_registration = EventAttendance::where('event_id', $row->event->id)
                                                        ->where('user_id', $row->user->id)
                                                        ->where('attendance_date', date('Y-m-d'))
                                                        ->exists();

                    return "<div class='form-check form-switch form-switch-success'>
                                <input class='attendance-checkbox form-check-input' style='width: 3em !important;' data-event-id='{$row->event->id}' data-user-id='{$row->user->id}' type='checkbox' role='switch' id='SwitchCheck3' ". ($event_registration ? 'checked' : '') .">
                            </div>";

                    // return "<input type='checkbox' ". ($event_registration ? 'checked' : '') ." class='attendance-checkbox'  />";
                })
                ->addColumn('actions', function ($row) {
                    $actions = "<div class='hstack gap-2'>
                        <a href='" . route('events.registrations.show', ['id' => $row->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Show'><i class='ri-eye-fill align-bottom'></i></a>
                        <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $row->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-5-fill align-bottom'></i></button>
                    </div>";

                    return $actions;
                })
                ->rawColumns(['actions', 'user', 'event', 'status', 'attendance_status'])
                ->make(true);
        }

        return view('pages.event-registrations.index', compact('event'));
    }

    public function show(Request $request, $id) {
        $event_registration = EventRegistration::findOrFail($id);

        return view('pages.event-registrations.show', compact('event_registration'));
    }
    
    public function register(Request $request) {
        $endPoint = "Event Registration";
        $event = Event::where('id', $request->event_id)->first();

        // Return 404 if the event is not for registration
        if(!$event->is_enable_event_registration) abort(404);

        return view('pages.events.register', compact('endPoint', 'event'));
    }

    public function save_registration(StoreRequest $request) {
        try {
            DB::beginTransaction();
            $event = Event::where('id', $request->event_id)->first();
            $users_count = count($request->users);
            $auth_user = Auth::user();

            $convenience_fee = 10.00;

            // Add the request donation in total amount
            $total_amount = (0 + $convenience_fee) + $request->donation;

            for ($i=0; $i < $users_count; $i++) { 
                $total_amount += $event->reg_fee;
            }
            
            $transaction_code = generateTransactionCode();
            $reference_code = generateReferenceCode();

            $transaction = Transaction::create([
                'transaction_code' => $transaction_code,
                'reference_code' => $reference_code,
                'donation' => $request->donation,
                'convenience_fee' => $convenience_fee,
                'sub_amount' => $event->reg_fee,
                'total_amount' => $total_amount,
                'payment_mode' => "N/A",
                'payment_type' => PaymentType::EVENT_REGISTRATION,
                'status' => 'pending', 
            ]);

            foreach ($request->users as $user_id) {
                $user = User::where('id', $user_id)->first();

                $event_registration = EventRegistration::create([
                    'transaction_id' => $transaction->id,
                    'event_id' => $event->id,
                    'mfc_id_number' => $user->mfc_id_number,
                    'amount' => $event->reg_fee,
                    'registered_by' => $auth_user->id,
                    'registered_at' => Carbon::now(),
                ]);

                EventUserDetail::create([
                    'event_registration_id' => $event_registration->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'contact_number' => $user->contact_number,
                    'address' => $user->address,
                ]);
            }

            $paymaya_user_details = [
                'firstname' => $auth_user->first_name,
                'lastname' => $auth_user->last_name,
            ];

            $payment_request_model = $this->paymayaService->createRequestModel($transaction, $paymaya_user_details);
            $payment_response = $this->paymayaService->pay($payment_request_model);

            $transaction->update([
                'checkout_id' => $payment_response['checkoutId'],
                'payment_link' => $payment_response['redirectUrl'],
            ]);
            
            DB::commit();

            return redirect($payment_response['redirectUrl']);
    
        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('fail', $exception->getMessage());
        }
    }    
}
