<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use App\Models\Section;
use App\Services\ExceptionHandlerService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EventsController extends Controller
{
    private $exceptionHandler;

    public function __construct(ExceptionHandlerService $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(! auth()->user()->hasRole('super_admin'), 403);

        $endPoint = 'list';

        if ($request->ajax()) {
            // Optimize by eager loading sections
            $events = Event::with('sections')->get();

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
                    $sectionColors = [
                        'kids' => '#fa6b02',
                        'youth' => '#0066ab',
                        'singles' => '#1c8265',
                        'servants' => '#ffad09',
                        'handmaids' => '#ee2c2e',
                        'couples' => '#2a81d9',
                    ];

                    $output = "<div class='d-flex flex-wrap gap-1'>";

                    foreach ($event->sections as $section) {
                        $color = $sectionColors[$section->name] ?? '#7852a9'; // Default color if not found
                        $output .= "<div class='badge' style='background: $color'>{$section->name}</div>";
                    }

                    $output .= "</div>";
                    return $output;
                })
                ->rawColumns(['actions', 'section'])
                ->make(true);
        }

        $sections = Section::all(); // Use all() instead of get()
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

            if (! $request->ajax())
                throw new Exception("Error processing data.", 400);

            $data = $request->validated();

            $filename = null;
            $file = null;

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
                'section_ids' => $request->section_ids,
                'poster' => $filename,
                'area' => $request->type == 5 ? auth()->user()->area : null,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_open_for_non_community' => $request->has('is_open_for_non_community'),
                'is_enable_event_registration' => $request->has('is_enable_event_registration'),
            ]));

            $file->move(public_path('uploads/events'), $filename); // Store the poster file in the uploads directory

            DB::commit();

            return response()->json(['message' => 'Event Created Successfully'], 200);

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->exceptionHandler->__handler($request, $exception);
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
        $events = Event::query()->active();
        $user = auth()->user();
        $user_section_id = $user->section_id;
        $user_area = $user->area;

        if ($request->query('filter') && $request->query('filter') === "upcoming_events") {
            $today = Carbon::today()->toDateString();
            $events = $events->where("start_date", '>', $today)->orderBy("start_date", "asc");
        } else if ($request->query('filter') && $request->query('filter') === "member_events") {
            $today = Carbon::today()->format('Y-m-d');

            $events_for_you = Event::where('start_date', '>', $today)
                ->whereJsonContains('section_ids', (string) $user_section_id)
                ->orderBy("start_date", "asc")
                ->get();

            $other_events = Event::where('start_date', '>', $today)
                ->where(function ($query) use ($user_area) {
                    $query->whereIn('type', [1, 2, 3, 4])
                        ->orWhere('area', $user_area)
                        ->orderBy("start_date", "asc");
                })->get();

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

        // Define section color mapping
        $sectionColors = [
            'kids' => '#fa6b02',
            'youth' => '#0066ab',
            'singles' => '#1c8265',
            'servants' => '#ffad09',
            'handmaids' => '#ee2c2e',
            'couples' => '#2a81d9',
        ];

        $events = Event::where('status', 'Active')
            ->when(! $user->hasRole('super_admin'), function ($q) use ($user_section_id, $user_area) {
                $q->where(function ($subquery) use ($user_section_id, $user_area) {
                    $subquery->whereJsonContains('section_ids', (string) $user_section_id)
                        ->orWhereIn('type', [1, 2, 3, 4])
                        ->where('area', $user_area)
                        ->whereNotNull('area');
                });
            })
            ->get()
            ->map(function ($event) use ($sectionColors) {

                // Fetch section names for the event
                $sectionNames = Section::whereIn('id', $event->section_ids)->pluck('name')->toArray();

                // Get colors based on section names
                $colors = array_map(function ($name) use ($sectionColors) {
                    return $sectionColors[$name] ?? '#7852a9';  // Default color if not found
                }, $sectionNames);

                // Determine background color
                $background = count($colors) > 1 ? '#7852a9' : $colors[0];

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
                        'section_ids' => $event->section_ids,
                    ],
                    'allDay' => true,
                ];
            });

        return response()->json([
            'events' => $events
        ]);
    }


}
