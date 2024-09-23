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
        abort_if(!auth()->user()->hasRole('super_admin'), 403);

        $endPoint = 'list';

        if ($request->ajax()) {
            $events = Event::get();
            return DataTables::of($events)
                ->editColumn('start_date', function ($event) {
                    return Carbon::parse($event->start_date)->format('F d, Y');
                })
                ->addColumn('actions', function ($event) {
                    $actions = "<div class='hstack gap-2'>
                        <a href='" . route('events.registrations.index', ['event' => $event->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Registration List'><i class='ri-file-list-3-line align-bottom'></i></a>
                        <button type='button' class='btn btn-soft-success btn-sm edit-btn' id='" . $event->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='View'><i class='ri-pencil-fill align-bottom'></i></button>
                        <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $event->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-5-fill align-bottom'></i></button>
                    </div>";

                    return $actions;
                })
                ->addColumn('section', function ($event) {
                    $sections = Section::whereIn('id', $event->section_ids)->get();
                    $output = "<div class='d-flex flex-wrap gap-1'>";

                    foreach ($sections as $section) {
                        switch ($section->name) {
                            case 'kids':
                                $color = '#fa6b02';
                                break;
                            case 'youth':
                                $color = '#0066ab';
                                break;
                            case 'singles':
                                $color = '#1c8265';
                                break;
                            case 'servants':
                                $color = '#ffad09';
                                break;
                            case 'handmaids':
                                $color = '#ee2c2e';
                                break;
                            case 'couples':
                                $color = '#2a81d9';
                                break;
                            default:
                                $color = '#7852a9';
                                break;
                        }

                        $output .= "<div class='badge' style='background: $color '>$section->name</div>";
                    }

                    $output .= "</div>";
                    // // Return 'N/A' if no section is found
                    // return $sections ? $sections->name : 'N/A';
                    return $output;
                })
                ->rawColumns(['actions', 'section'])
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

            if (!$request->ajax())
                throw new Exception("Error processing data.", 400);
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

            Event::create(attributes: array_merge($data, [
                'section_ids' => $request->section_ids,
                'poster' => $filename,
                'area' => $request->type == 5 ? auth()->user()->area : null,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_open_for_non_community' => $request->has('is_open_for_non_community'),
                'is_enable_event_registration' => $request->has('is_enable_event_registration'),
            ]));

            $file->move(public_path('uploads'), $filename); // Store the poster file in the uploads directory

            DB::commit();

            return response()->json(['message' => 'Event Created Successfully'], 200);

        } catch (Exception $exception) {
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
        if (is_numeric($request->identifier)) {
            $event = Event::findOrFail($identifier);
        } else {
            $event = Event::where('title', $identifier)->firstOrFail();
        }

        if ($request->ajax() || $request->header('application/json')) {
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
            'section_ids' => $request->has('section_ids') ? $request->section_ids : null,
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

    public function calendar(Request $request)
    {
        return view('apps-calendar');
    }

    public function all(Request $request)
    {   
        $events = Event::query();
        $user = auth()->user();
        $user_section_id = $user->section_id;
        $user_area = $user->area;

        if ($request->query('filter') && $request->query('filter') === "upcoming_events") {
            $today = Carbon::today()->toDateString();
            $events = $events->where("start_date", '>', $today)
                    ->when(!$user->hasRole('super_admin'), function ($q) use ($user_section_id, $user_area) {
                        $q->where(function ($subquery) use ($user_section_id, $user_area) {
                            $subquery->whereJsonContains('section_ids', (string) $user_section_id)
                                ->orWhereIn('type', [1, 2, 3, 4]) // worldwide, national, regional, ncr
                                ->orWhere('area', $user_area); // Same area events
                        });
                    })
                    ->orderBy('start_date');
        } else if($request->query('filter') && $request->query('filter') === "member_events") {
            $today = Carbon::today()->format('Y-m-d');
            
            $events_for_you = Event::where('start_date', '>', $today)
                                ->whereJsonContains('section_ids', (string) $user_section_id)
                                // ->orWhereIn('type', [1, 2, 3, 4])
                                ->orderBy("start_date", "asc")
                                ->get();

            $other_events = Event::where('start_date', '>', $today)
                                ->where('area', $user_area)
                                ->orWhereIn('type', [1, 2, 3, 4])
                                ->get();
            
            return response()->json([
                'status' => 'success',
                'events_for_you' => $events_for_you,
                'other_events' => $other_events
            ]);
        }

        $events = $events->get();

        return response()->json([
            'status' => 'success',
            'events' => $events,
        ], 200);
    }

    public function fullCalendar(Request $request)
    {
        $user = auth()->user();
        $user_section_id = $user->section_id;
        $user_area = $user->area;

        $events = Event::where('status', 'Active')
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user_section_id, $user_area) {
                $q->where(function ($subquery) use ($user_section_id, $user_area) {
                    $subquery->whereJsonContains('section_ids', (string) $user_section_id)
                        ->orWhereIn('type', [1, 2, 3, 4]) // worldwide, national, regional, ncr
                        ->orWhere('area', $user_area); // Same area events
                });
            })
            ->get()
            ->map(function ($event) {

                $sections = Section::whereIn('id', $event->section_ids)->get();

                $colors = [];
                foreach ($sections as $key => $section) {
                    switch ($section->name) {
                        case 'kids':
                            $color = '#fa6b02';
                            $image = '<img src="' . asset('build/images/kids-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        case 'youth':
                            $color = '#0066ab';
                            $image = '<img src="' . asset('build/images/youth-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        case 'singles':
                            $color = '#1c8265';
                            $image = '<img src="' . asset('build/images/singles-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        case 'servants':
                            $color = '#ffad09';
                            $image = '<img src="' . asset('build/images/servant-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        case 'handmaids':
                            $color = '#ee2c2e';
                            $image = '<img src="' . asset('build/images/handmaids-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        case 'couples':
                            $color = '#2a81d9';
                            $image = '<img src="' . asset('build/images/couples-logo.png') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                        default:
                            $color = '#7852a9';
                            $image = '<img src="' . asset('build/images/MFC-Logo.jpg') . '" width="20" height="20" style="border-radius: 50%;" />';
                            break;
                    }
                    array_push($colors, $color);
                }

                $percentage = 100 / count($colors);
                $colorStops = [];
                foreach ($colors as $index => $color) {
                    $colorStops[] = "$color";
                }

                if (count($colors) > 1) {
                    $background = "#7852a9";
                    $image = '<img src="' . asset('build/images/MFC-Logo.jpg') . '" width="20" height="20" style="border-radius: 50%;" />';
                } else {
                    $background = $colors[0];
                }

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => Carbon::parse($event->end_date)->addDay(),
                    'extendedProps' => [
                        'time' => $event->time,
                        'location' => $event->location,
                        'latitude' => $event->latitude,
                        'longitude' => $event->longitude,
                        'description' => $event->description,
                        'registration_fee' => $event->reg_fee,
                        'is_enable_event_registration' => $event->is_enable_event_registration,
                        'background' => $background,
                    ],
                    'allDay' => true
                ];
            });

        return response()->json([
            'events' => $events
        ]);
    }

}
