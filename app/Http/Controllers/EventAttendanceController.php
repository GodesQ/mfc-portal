<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventAttendanceController extends Controller
{
    public function getEventUsers(Request $request) {
        $users = User::query();
        $event_attendances = EventAttendance::where("event_id", $request->event_id)->get();

        if($request->query('search')) {
            $searchQuery = $request->query('search');
            $users = $users->where('mfc_id_number', $searchQuery)
                        ->orWhere('first_name', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', '%' . $searchQuery . '%');
        }

        $users = $users->with('section')->get();

        $event_dates = $this->getEventDateRange($request->event_id);

        // Get all event attendances for the specific event
        $event_attendances = EventAttendance::where("event_id", $request->event_id)->pluck('user_id')->toArray();

        // Add a 'checked' property to each user if they are in the event attendances
        $users = $users->map(function ($user) use ($event_attendances, $event_dates) {
            $user->checked = in_array($user->id, $event_attendances);
            return $user;
        });

        return response()->json([
            "message" => "success",
            "users" => $users,
        ]); 
    }

    public function saveAttendance(Request $request) {
        $user = User::where("id", $request->user_id)->first();
        $event = Event::where("id", $request->event_id)->first();
        
        if($request->checked) {
            EventAttendance::updateOrCreate([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'attendance_date' => Carbon::now()
            ], []);
        } else {
            EventAttendance::where('event_id', $request->event_id)->where('user_id', $request->user_id)->delete();
        }

        return response()->json([
            "status" => "success",
            "message" => "Attendance recorded successfully",
        ]);
    }

    public function report(Request $request) {
        $event = Event::where('id', $request->event_id)->first();

        $event_attendance = EventAttendance::select('attendance_date', DB::raw("COUNT(*) as user_count"))
                                ->where('event_id', $request->event_id)
                                ->groupBy('attendance_date')
                                ->get();
        $endPoint = "Report";

        return view('pages.reports.event-attendance-report', compact('endPoint', 'event', 'event_attendance'));
    }

    private function getEventDateRange($event_id) {
        // Fetch the event based on the provided event_id
        $event = Event::where('id', $event_id)->first();
    
        // Check if event exists
        if (!$event) {
            return []; // Return an empty array if no event found
        }
    
        // Initialize the start and end dates from the event
        $start_date = new \DateTime($event->start_date);
        $end_date = new \DateTime($event->end_date);
        
        // Include the end date in the range
        $end_date->modify('+1 day');
    
        // Initialize the array to store the dates
        $date_range = [];
    
        // Loop through the dates from start to end
        while ($start_date < $end_date) {
            // Add the current date to the array
            $date_range[] = $start_date->format('Y-m-d');
            
            // Move to the next day
            $start_date->modify('+1 day');
        }
    
        return $date_range;
    }
}
