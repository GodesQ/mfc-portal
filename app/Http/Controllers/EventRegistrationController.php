<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Http\Requests\EventRegistration\StoreRequest;
use App\Models\Event;
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

class EventRegistrationController extends Controller
{   
    public $paymayaService;
    public function __construct(PaymayaService $paymayaService) {
        $this->paymayaService = $paymayaService;
    }
    
    public function register(Request $request) {
        $endPoint = "Event Registration";
        $event = Event::where('id', $request->event_id)->first();

        if(!$event->is_enable_event_registration) abort(404);

        return view('pages.events.register', compact('endPoint', 'event'));
    }

    public function save_registration(StoreRequest $request) {
        try {
            DB::beginTransaction();
            $event = Event::where('id', $request->event_id)->first();
            $users_count = count($request->users);
            $auth_user = Auth::user();

            // Add the request donation in total amount
            $total_amount = 0 + $request->donation;

            for ($i=0; $i < $users_count; $i++) { 
                $total_amount += $event->reg_fee;
            }
            
            $transaction_code = generateTransactionCode();
            $reference_code = generateReferenceCode();

            $transaction = Transaction::create([
                'transaction_code' => $transaction_code,
                'reference_code' => $reference_code,
                'donation' => $request->donation,
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
