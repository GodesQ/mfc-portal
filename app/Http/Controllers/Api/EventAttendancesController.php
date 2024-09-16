<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventAttendance;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class EventAttendancesController extends Controller
{
    public function saveAttendance(Request $request) {
        try {
            $registration_code = $request->registration_code;

            $event_registration = EventRegistration::where("registration_code", $registration_code)->first();
            if(!$event_registration) throw new Exception("Registration Not Found.", 404);
            
            $event_attendance = EventAttendance::where("event_id", $event_registration->event_id)
                                ->where("user_id", $event_registration->user->id)
                                ->exists();
                                
            if($event_attendance) throw new Exception("The user has already been marked as present for this event. No further attendance record can be added.", 400);

            $attendance = EventAttendance::create([
                'event_id' => $event_registration->event->id,
                'user_id' => $event_registration->user->id,
                'attendance_date' => Carbon::now()
            ]);

            return response()->json([
                "message" => "Attendance Saved Successfully.",
                "attendance" => $attendance,
            ]);

        } catch (Exception $e) {
            $exception_code = $e->getCode() === 0 ? 500 : $e->getCode();
            return response()->json([
                "message"=> $e->getMessage(),
                "error" => $e,
            ], $exception_code);
        }
    }
}
