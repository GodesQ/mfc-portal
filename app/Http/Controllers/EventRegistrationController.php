<?php

namespace App\Http\Controllers;

use App\Enum\PaymentType;
use App\Http\Requests\EventRegistration\GuestStoreRequest;
use App\Http\Requests\EventRegistration\StoreRequest;
use App\Mail\EarlyBirdDiscountMail;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventRegistration;
use App\Models\EventUserDetail;
use App\Models\Section;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PaymayaService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str as SupportStr;
use Str;
use Yajra\DataTables\DataTables;

class EventRegistrationController extends Controller
{
    public $paymayaService;
    public function __construct(PaymayaService $paymayaService)
    {
        $this->paymayaService = $paymayaService;
    }

    public function list(Request $request, Event $event)
    {

        if ($request->ajax()) {
            $registrations = EventRegistration::where('event_id', $event->id)
                ->with('user', 'event', 'transaction', 'event_user_detail');

            return DataTables::of($registrations)
                ->addColumn('user', function ($row) {
                    $identifier = $row->display_mfc_id_number === 'Guest User'
                        ? $row->display_mfc_id_number
                        : '#' . $row->display_mfc_id_number;

                    return '<h6 style="line-height: 5px !important;" class="fw-semibold">' . e($row->display_name) . '</h6>
                            <small>' . e($identifier) . '</small>';
                })
                ->addColumn('event', function ($row) {
                    return $row->event->title;
                })
                ->editColumn('amount', function ($row) {
                    return "₱ " . number_format($row->amount, 2);
                })
                ->addColumn('status', function ($row) {
                    if (optional($row->transaction)->status == 'paid') {
                        return "<div class='badge bg-success'>{$row->transaction->status}</div>";
                    } else {
                        return "<div class='badge bg-warning'>" . e($row->transaction->status ?? 'pending') . "</div>";
                    }
                })
                ->addColumn('attendance_status', function ($row) {
                    if (!$row->user) {
                        return "<div class='badge bg-secondary'>Guest attendee</div>";
                    }

                    $event_registration = EventAttendance::where('event_id', $row->event->id)
                        ->where('user_id', $row->user->id)
                        ->where('attendance_date', date('Y-m-d'))
                        ->exists();

                    return "<div class='form-check form-switch form-switch-success'>
                                <input class='attendance-checkbox form-check-input' style='width: 3em !important;' data-event-id='{$row->event->id}' data-user-id='{$row->user->id}' type='checkbox' role='switch' id='SwitchCheck3' " . ($event_registration ? 'checked' : '') . ">
                            </div>";

                    // return "<input type='checkbox' ". ($event_registration ? 'checked' : '') ." class='attendance-checkbox'  />";
                })
                ->addColumn('actions', function ($row) {
                    $actions = "<div class='hstack gap-2'>
                        <button type='button' class='btn btn-soft-primary btn-sm qr-btn' data-registration-code='" . $row->registration_code . "' id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='.bs-example-modal-center' data-bs-toggle='tooltip' data-bs-placement='top' title='QR Code'><i class='ri-qr-code-line'></i></button>
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

    public function show(Request $request, $id)
    {
        $event_registration = EventRegistration::with('event', 'transaction', 'user.user_details', 'event_user_detail')->findOrFail($id);

        return view('pages.event-registrations.show', compact('event_registration'));
    }

    public function register(Request $request)
    {
        $endPoint = "Event Registration";
        $event = Event::where('id', $request->event_id)->first();

        $this->ensurePublicRegistrationAvailable($event);

        return view('pages.events.register', compact('endPoint', 'event'));
    }

    public function publicEntry(Event $event)
    {
        $this->ensurePublicRegistrationAvailable($event);

        if (Auth::check()) {
            return redirect()->route('events.register', ['event_id' => $event->id]);
        }

        return view('pages.events.register-public', compact('event'));
    }

    public function memberEntry(Event $event)
    {
        $this->ensurePublicRegistrationAvailable($event);

        if (Auth::check()) {
            return redirect()->route('events.register', ['event_id' => $event->id]);
        }

        return Redirect::guest(route('login'));
    }

    public function guestEntry(Event $event)
    {
        $this->ensurePublicRegistrationAvailable($event);

        if (Auth::check()) {
            return redirect()->route('events.register', ['event_id' => $event->id]);
        }

        $sections = Section::query()->orderBy('name')->get(['id', 'name']);

        return view('pages.events.register-guest', compact('event', 'sections'));
    }

    public function saveGuestRegistration(GuestStoreRequest $request, Event $event)
    {
        $earlyBirdNotification = null;

        try {
            DB::beginTransaction();

            $this->ensurePublicRegistrationAvailable($event);

            $primaryAttendee = [
                'first_name' => $request->payer_first_name,
                'last_name' => $request->payer_last_name,
                'email' => $request->payer_email,
                'contact_number' => $request->payer_contact_number,
                'tshirt_size' => $request->payer_tshirt_size,
                'mfc_section' => $request->payer_mfc_section,
                'area' => $request->payer_area,
                'address' => $request->payer_address,
            ];

            $additionalAttendees = collect($request->validated('attendees', []));
            $attendees = collect([$primaryAttendee])->merge($additionalAttendees->values());
            $donation = (float) $request->input('donation', 0);
            $convenienceFee = 10.00;
            $totalConvenienceFee = $attendees->count() * $convenienceFee;
            $pricing = $this->calculateRegistrationPricing($event, $attendees->count(), 0);
            $registrationSubtotal = $pricing['sub_amount'];
            $totalAmount = $pricing['total_amount'] + $donation + $totalConvenienceFee;

            $this->checkExistingGuestRegistrationRecord($event, $attendees->all());

            $transaction = Transaction::create([
                'transaction_code' => generateTransactionCode(),
                'reference_code' => generateReferenceCode(),
                'received_from_id' => null,
                'payer_first_name' => $request->payer_first_name,
                'payer_last_name' => $request->payer_last_name,
                'payer_email' => $request->payer_email,
                'payer_contact_number' => $request->payer_contact_number,
                'donation' => $donation,
                'convenience_fee' => $totalConvenienceFee,
                'sub_amount' => $registrationSubtotal,
                'early_bird_discount' => $pricing['early_bird_discount'],
                'total_amount' => $totalAmount,
                'payment_mode' => 'N/A',
                'payment_type' => PaymentType::EVENT_REGISTRATION,
                'status' => 'pending',
            ]);

            foreach ($attendees as $index => $attendee) {
                $eventRegistration = EventRegistration::create([
                    'registration_code' => 'REG' . date('y-m') . '-' . Str::upper(Str::random(7)),
                    'transaction_id' => $transaction->id,
                    'event_id' => $event->id,
                    'user_id' => null,
                    'mfc_id_number' => null,
                    'amount' => $pricing['amounts'][$index],
                    'early_bird_discount' => $pricing['discounts'][$index],
                    'registered_by' => null,
                    'registered_at' => Carbon::now(),
                ]);

                EventUserDetail::create([
                    'event_registration_id' => $eventRegistration->id,
                    'user_type' => $index === 0 ? 'primary' : 'normal',
                    'first_name' => $attendee['first_name'],
                    'last_name' => $attendee['last_name'],
                    'email' => $attendee['email'],
                    'contact_number' => $attendee['contact_number'],
                    'tshirt_size' => $attendee['tshirt_size'] ?? null,
                    'mfc_section' => $attendee['mfc_section'] ?? null,
                    'area' => $attendee['area'] ?? null,
                    'address' => $attendee['address'],
                ]);
            }

            $earlyBirdNotification = $this->buildEarlyBirdNotificationPayload(
                $event,
                $pricing['early_bird_discount'],
                $request->payer_email,
                $request->payer_first_name
            );

            $paymentRequestModel = $this->paymayaService->createRequestModel($transaction, [
                'firstname' => $request->payer_first_name,
                'lastname' => $request->payer_last_name,
            ]);

            $paymentResponse = $this->paymayaService->pay($paymentRequestModel);

            $transaction->update([
                'checkout_id' => $paymentResponse['checkoutId'],
                'payment_link' => $paymentResponse['redirectUrl'],
            ]);

            DB::commit();

            $this->sendEarlyBirdNotification($earlyBirdNotification);

            return redirect($paymentResponse['redirectUrl']);
        } catch (Exception $exception) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'guest_registration' => $exception->getMessage(),
            ]);
        }
    }

    public function save_registration(StoreRequest $request)
    {
        $earlyBirdNotification = null;

        try {
            DB::beginTransaction();
            $event = Event::where('id', $request->event_id)->first();
            $users_count = count($request->users);
            $auth_user = Auth::user();
            $selectedUsers = User::whereIn('id', $request->users)->get()->keyBy('id');
            $primaryUserIndex = $this->determinePrimaryMemberIndex($request->users, $auth_user);

            $convenience_fee = 10.00;
            $total_convenience_fee = 0;

            for ($i = 0; $i < $users_count; $i++) {
                $total_convenience_fee += $convenience_fee;
            }

            $this->checkExistingRegistrationRecord($request);

            $pricing = $this->calculateRegistrationPricing($event, $users_count, $primaryUserIndex);
            $total_amount = $pricing['total_amount'] + $total_convenience_fee + (float) $request->donation;

            $transaction_code = generateTransactionCode();
            $reference_code = generateReferenceCode();

            $transaction = Transaction::create([
                'transaction_code' => $transaction_code,
                'reference_code' => $reference_code,
                'received_from_id' => auth()->user()->id,
                'payer_first_name' => $auth_user->first_name,
                'payer_last_name' => $auth_user->last_name,
                'payer_email' => $auth_user->email,
                'payer_contact_number' => $auth_user->contact_number,
                'donation' => $request->donation,
                'convenience_fee' => $total_convenience_fee,
                'sub_amount' => $pricing['sub_amount'],
                'early_bird_discount' => $pricing['early_bird_discount'],
                'total_amount' => $total_amount,
                'payment_mode' => "N/A",
                'payment_type' => PaymentType::EVENT_REGISTRATION,
                'status' => 'pending',
            ]);

            foreach ($request->users as $index => $user_id) {
                $user = $selectedUsers->get($user_id);

                $registration_code = "REG" . date("y-m") . "-" . Str::upper(Str::random(7));
                $isPrimaryUser = $index === $primaryUserIndex;

                $event_registration = EventRegistration::create([
                    "registration_code" => $registration_code,
                    'transaction_id' => $transaction->id,
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'mfc_id_number' => $user->mfc_id_number,
                    'amount' => $pricing['amounts'][$index],
                    'early_bird_discount' => $pricing['discounts'][$index],
                    'registered_by' => $auth_user->id,
                    'registered_at' => Carbon::now(),
                ]);

                EventUserDetail::create([
                    'event_registration_id' => $event_registration->id,
                    'user_type' => $isPrimaryUser ? 'primary' : 'normal',
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'contact_number' => $user->contact_number,
                    'tshirt_size' => null,
                    'mfc_section' => optional($user->section)->name,
                    'area' => $user->area,
                    'address' => optional($user->user_details)->address,
                ]);
            }

            $primaryUser = $selectedUsers->get($request->users[$primaryUserIndex] ?? null);
            $earlyBirdNotification = $this->buildEarlyBirdNotificationPayload(
                $event,
                $pricing['early_bird_discount'],
                $primaryUser?->email,
                $primaryUser?->first_name
            );

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

            $this->sendEarlyBirdNotification($earlyBirdNotification);

            return redirect($payment_response['redirectUrl']);

        } catch (Exception $exception) {
            DB::rollBack();
            return back()->with('fail', $exception->getMessage());
        }
    }

    public function userRegistrations(Request $request, $user_id)
    {
        if ($request->ajax()) {
            $registrations = EventRegistration::whereHas('user', function ($query) use ($user_id) {
                $query->where('id', $user_id);
            })->with('user', 'event', 'transaction', 'registered_by_user', 'event_user_detail');

            return DataTables::of($registrations)
                ->addColumn('event', function ($row) {
                    return $row->event->title;
                })
                ->editColumn('amount', function ($row) {
                    return "₱ " . number_format($row->amount, 2);
                })
                ->addColumn('status', function ($row) {
                    if (optional($row->transaction)->status == 'paid') {
                        return "<div class='badge bg-success'>{$row->transaction->status}</div>";
                    } else {
                        return "<div class='badge bg-warning'>" . e($row->transaction->status ?? 'pending') . "</div>";
                    }
                })
                ->editColumn("registered_at", function ($row) {
                    return Carbon::parse($row->registered_at)->format("F d, Y");
                })
                ->editColumn("registered_by", function ($row) {
                    return trim(($row->registered_by_user->first_name ?? '') . " " . ($row->registered_by_user->last_name ?? '')) ?: 'Guest User';
                })
                ->addColumn('actions', function ($row) {
                    $actions = "<div class='hstack gap-2'>
                        <button type='button' class='btn btn-soft-primary btn-sm qr-btn' data-registration-code='" . $row->registration_code . "' id='" . $row->id . "' data-bs-toggle='modal' data-bs-target='.bs-example-modal-center' data-bs-toggle='tooltip' data-bs-placement='top' title='QR Code'><i class='ri-qr-code-line'></i></button>
                        <a href='" . route('events.registrations.show', ['id' => $row->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Show'><i class='ri-eye-fill align-bottom'></i></a>
                    </div>";

                    return $actions;
                })
                ->rawColumns(['actions', 'user', 'event', 'status'])
                ->make(true);

        }

        return view('pages.event-registrations.users-index');
    }

    private function checkExistingRegistrationRecord($request)
    {
        $event_registration = false;

        foreach ($request->users as $key => $user_id) {
            $event_registration = EventRegistration::where('event_id', $request->event_id)
                ->where('user_id', $user_id)
                ->exists();

            if ($event_registration)
                break;
        }

        if ($event_registration)
            throw new Exception('One or more users in the list have already completed registration for this event. Duplicate registrations are not allowed.', 400);
    }

    private function checkExistingGuestRegistrationRecord(Event $event, array $attendees): void
    {
        foreach ($attendees as $attendee) {
            $normalizedFirstName = $this->normalizeGuestIdentityValue($attendee['first_name'] ?? null);
            $normalizedLastName = $this->normalizeGuestIdentityValue($attendee['last_name'] ?? null);
            $normalizedEmail = $this->normalizeGuestIdentityValue($attendee['email'] ?? null);

            $exists = EventRegistration::query()
                ->where('event_id', $event->id)
                ->whereNull('user_id')
                ->whereHas('event_user_detail', function ($query) use ($normalizedFirstName, $normalizedLastName, $normalizedEmail) {
                    $query
                        ->whereRaw('LOWER(TRIM(first_name)) = ?', [$normalizedFirstName])
                        ->whereRaw('LOWER(TRIM(last_name)) = ?', [$normalizedLastName])
                        ->whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail]);
                })
                ->exists();

            if ($exists) {
                throw new Exception('One or more attendees have already completed registration for this event. Duplicate registrations are not allowed.', 400);
            }
        }
    }

    private function ensurePublicRegistrationAvailable(?Event $event): void
    {
        abort_if(
            !$event
            || !$event->is_enable_event_registration
            || strcasecmp((string) $event->status, 'Active') !== 0,
            404
        );
    }

    private function normalizeGuestIdentityValue(?string $value): string
    {
        return SupportStr::of((string) $value)->trim()->lower()->value();
    }

    private function calculateRegistrationPricing(Event $event, int $attendeeCount, int $primaryIndex): array
    {
        $registrationFee = (float) $event->reg_fee;
        $subAmount = $registrationFee * $attendeeCount;
        $appliedDiscount = $this->resolveEarlyBirdDiscount($event);
        $discounts = array_fill(0, $attendeeCount, 0.00);
        $amounts = array_fill(0, $attendeeCount, $registrationFee);

        if ($attendeeCount > 0 && $appliedDiscount > 0 && array_key_exists($primaryIndex, $amounts)) {
            $discounts[$primaryIndex] = $appliedDiscount;
            $amounts[$primaryIndex] = max($registrationFee - $appliedDiscount, 0);
        }

        return [
            'sub_amount' => $subAmount,
            'early_bird_discount' => $appliedDiscount,
            'discounts' => $discounts,
            'amounts' => $amounts,
            'total_amount' => array_sum($amounts),
        ];
    }

    private function resolveEarlyBirdDiscount(Event $event): float
    {
        if (!$event->is_early_bird_enabled) {
            return 0.00;
        }

        $registrationFee = (float) $event->reg_fee;
        if ($registrationFee <= 0) {
            return 0.00;
        }

        return min((float) $event->early_bird_discount, $registrationFee);
    }

    private function determinePrimaryMemberIndex(array $userIds, User $authUser): int
    {
        $normalizedUserIds = array_map('intval', $userIds);
        $authIndex = array_search($authUser->id, $normalizedUserIds, true);

        return $authIndex === false ? 0 : (int) $authIndex;
    }

    private function buildEarlyBirdNotificationPayload(Event $event, float $discount, ?string $email, ?string $name): ?array
    {
        if ($discount <= 0 || blank($email)) {
            return null;
        }

        return [
            'email' => $email,
            'name' => $name ?: 'Attendee',
            'event_title' => $event->title,
            'discount' => $discount,
        ];
    }

    private function sendEarlyBirdNotification(?array $payload): void
    {
        if (!$payload) {
            return;
        }

        try {
            Mail::to($payload['email'])->send(
                new EarlyBirdDiscountMail($payload['name'], $payload['event_title'], $payload['discount'])
            );
        } catch (Exception $exception) {
            report($exception);
        }
    }
}
