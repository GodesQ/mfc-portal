<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use App\Models\Section;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EventsController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $endPoint = 'list';
        $events = Event::all();

        if ($request->ajax()) {
            return DataTables::of($events)
                ->editColumn('start_date', function ($event) {
                    return Carbon::parse($event->start_date)->format('F d, Y');    
                })
                ->addColumn('actions', function ($event) {
                    $actions = "<div class='hstack gap-2'>
                        <a href='" . route('events.registrations.index', ['event' => $event->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Registration List'><i class='ri-file-list-3-line align-bottom'></i></a>
                        <a href='" . route('events.show', ['identifier' => $event->title]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Show'><i class='ri-eye-fill align-bottom'></i></a>
                        <button type='button' class='btn btn-soft-success btn-sm edit-btn' id='" . $event->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='View'><i class='ri-pencil-fill align-bottom'></i></button>
                        <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $event->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-5-fill align-bottom'></i></button>
                    </div>";

                    return $actions;
                })
                ->addColumn('section', function ($event) {
                    $section = Section::find($event->section_id);

                    // Return 'N/A' if no section is found
                    return $section ? $section->name : 'N/A';

                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $sections = Section::get();
        return view('pages.events.list', compact('endPoint', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {   
        try {
            DB::beginTransaction();

            if(!$request->ajax()) throw new Exception("Error processing data.", 400);
            $data = $request->validated();

            $filename = "";
            $file = "";

            if ($request->hasFile('poster')) {
                $file = $request->file('poster');
                $filename = time() . '_' . $file->getClientOriginalName();
            }

            $start_date = $request->event_date;
            $end_date = $request->event_date;

            if (strpos($request->event_date, 'to') !== false) {
                $dates = explode(' to ', $request->event_date);
                $start_date = $dates[0] ?? '';
                $end_date = $dates[1] ?? '';
            }

            Event::create(array_merge($data, [
                'poster' => $filename,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_open_for_non_community' => $request->has('is_open_for_non_community'),
                'is_enable_event_registration' => $request->has('is_enable_event_registration'),
            ]));

            $file->move(public_path('uploads'), $filename); // Store the poster file in the uploads directory

            DB::commit();

            return response()->json(['message' => 'Event Created Successfully'], 200);

        }catch (Exception $exception) {
            DB::rollBack();
            $exception_code = $exception->getCode() === 0 ? 500 : $exception->getCode();

            return response()->json([
                "message" => $exception->getMessage(),
            ], $exception_code);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $identifier)
    {   
        if(is_numeric($request->identifier)) {
            $event = Event::findOrFail($identifier);
        } else {
            $event = Event::where('title', $identifier)->firstOrFail();
        }

        if($request->ajax() || $request->header('application/json')) {
            return response()->json([
                'status' => 'success',
                'event' => $event,
            ]);
        }

        return view('pages.events.show', compact('event'));
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
    public function update(UpdateRequest $request, string $id)
    {   
        $data = $request->except('_token', '_method', 'eventid');
        $event = Event::findOrFail($id);

        $start_date = $request->event_date;
        $end_date = $request->event_date;

        if (strpos($request->event_date, 'to') !== false) {
            $dates = explode(' to ', $request->event_date);
            $start_date = $dates[0] ?? '';
            $end_date = $dates[1] ?? '';
        }

        $event->update(array_merge($data, [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]));

        return response()->json([
            'status' => TRUE,
            'message' => "Event Successfully Updated."
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        $old_upload_image = public_path('uploads/') . $event->poster;
        @unlink($old_upload_image);

        $event->delete();

        return response()->json([
            'status' => TRUE,
            'message' => "Event Successfully Deleted"
        ]);
    }

    public function calendar(Request $request) {
        return view('apps-calendar');
    }

    public function all(Request $request) {
        $events = Event::query();

        if($request->query('filter') && $request->query('filter') === "upcoming_events") {
            $today = Carbon::today()->toDateString();
            $events = $events->where("start_date", '>', $today);
        }

        $events = $events->with('section')->get();

        return response()->json([
            'status' => 'success',
            'events' => $events,
        ], 200);
    }

    public function fullCalendar(Request $request) {
        $events = Event::where('status', 'Active')->with('section')->get()->map(function($event) {

            switch($event->section->name) {
                case 'kids':
                    $classname = 'bg-orange-subtle';
                    break;
                case 'youth':
                    $classname = 'bg-blue-subtle';
                    break;
                case 'singles':
                    $classname = 'bg-success-subtle';
                    break;
                case 'servants':
                    $classname = 'bg-warning-subtle';
                        break;
                case 'handmaids':
                    $classname = 'bg-danger-subtle';
                        break;
                case 'couples':
                    $classname = 'bg-info-subtle';
                        break;       
                default:
                    $classname = 'bg-primary-subtle';
                        break;
            }


            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => Carbon::parse($event->end_date)->addDay(), 
                'className' => $classname,
                'extendedProps' => [
                    'time' => $event->time,
                    'location' => $event->location,
                    'latitude' => $event->latitude,
                    'longitude' => $event->longitude,
                    'description' => $event->description,
                    'registration_fee' => $event->reg_fee, 
                    'is_enable_event_registration' => $event->is_enable_event_registration,
                ],
                'allDay' => true
            ];
        });
    
        return response()->json([
            'events' => $events
        ]);
    }
    
}
