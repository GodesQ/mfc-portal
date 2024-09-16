<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EventAttendanceController extends Controller
{   
    public function index(Request $request) {
        if($request->ajax()) {
            $attendances = EventAttendance::select("user_id", DB::raw("MAX(event_id) as event_id"), DB::raw("MAX(attendance_date) as attendance_date"));

            $attendances = $attendances->where('event_id', $request->event_id)
                            ->groupBy("user_id");

            return DataTables::of($attendances)
                    ->addIndexColumn() 
                    ->editColumn("attendance_date",function ($row) {
                        return Carbon::parse($row->attendance_date)->format("M d, Y");
                    })
                    ->addColumn('user', function ($row) {
                        return '<h6 style="line-height: 5px !important;" class="fw-semibold">' . $row->user->first_name . ' ' . $row->user->last_name .'</h6>
                                <small>#' . $row->user->mfc_id_number .'</small>';
                    })
                    ->addColumn('event', function ($row) {
                        return '<h6 style="line-height: 5px !important;" class="fw-semibold">' .$row->event->title  .'</h6>
                                <small>' . Carbon::parse($row->event->start_date)->format('M d, Y') . " to " . Carbon::parse($row->event->end_date)->format('M d, Y') .'</small>';
                    })
                    ->addColumn('actions', function ($row) {
                        $actions = "<button type='button' class='btn btn-soft-danger btn-sm edit-btn' id='" . $row->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
                                            <i class='ri-delete-bin-5-fill align-bottom'></i>
                                    </button>";
    
                        return $actions;
                    })
                    ->rawColumns(['actions', "event", "user"])
                    ->make(true);
        }

        $events = Event::latest()->get();
        return view("pages.event-attendances.index", compact("events"));
    }

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
        try {
            $user = User::where("id", $request->user_id)->first();
            if(!$user) throw new Exception("User Not Found", 404);
            
            $event = Event::where("id", $request->event_id)->first();
            if(!$event) throw new Exception("Event Not Found", 404);

            if($request->checked) {
                EventAttendance::updateOrCreate([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'attendance_date' => Carbon::now()
                ], []);
            } else {
                EventAttendance::where('event_id', $request->event_id)
                    ->where('user_id', $request->user_id)
                    ->delete();
            }

            return response()->json([
                "status" => "success",
                "message" => $request->checked ? "Attendance recorded successfully" : "Attendance updated successfully",
            ]); 

        } catch (Exception $exception) {
            $exception_code = $exception->getCode() === 0 ? 500 : $exception->getCode();
            return response()->json([
                "errors" => [
                    "error" => $exception,
                    "message" => $exception->getMessage(),
                ]
            ], $exception_code);
        }
    }

    public function storeUser(Request $request) {
        DB::beginTransaction(); // Start transaction
    
        try {
            // Check if the event exists
            $event = Event::find($request->event_id);
            if(!$event) throw new Exception('No Event Found.',404);
    
            // Check if the user exists
            $user = User::find($request->user_id);
            if(!$user) throw new Exception('No User Found.',404);
    
            $attendance = null;
    
            // If event registration is enabled, ensure the user is registered
            if($event->is_enable_event_registration) {
                $user_event_registration = EventRegistration::where('event_id', $request->event_id)
                                        ->where('mfc_id_number', $user->mfc_id_number)
                                        ->exists();
    
                // If the user is registered, update or create the attendance
                if($user_event_registration) {
                    $attendance = EventAttendance::updateOrCreate([
                        'event_id' => $request->event_id,
                        'user_id' => $request->user_id,
                    ], []);
                } else {
                    throw new Exception('User is not registered for this event.', 400);
                }
            } else {
                // If registration is not enabled, allow adding attendance directly
                $attendance = EventAttendance::updateOrCreate([
                    'event_id' => $request->event_id,
                    'user_id' => $request->user_id,
                ], []);
            }
    
            DB::commit(); // Commit transaction
    
            return response()->json([
                'message' => "Attendance Added Successfully",
                'attendance' => $attendance,
            ]);
            
        } catch (Exception $exception) {
            DB::rollBack(); // Rollback in case of an error
            $exception_code = (int) ($exception->getCode() === 0 ? 500 : $exception->getCode());
    
            return response()->json([
                'errors'=> [
                    'message' => $exception->getMessage(),
                    'error' => $exception,
                ],
            ], $exception_code);
        }
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
