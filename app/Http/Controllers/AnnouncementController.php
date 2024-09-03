<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(Announcement::all());
        $announcements = Announcement::all();
        $services = Service::all();

        if ($request->ajax()) {
            $data = $announcements;

            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    $actions = "<div class='hstack gap-2'>
                        <a href='" . route('announcements.show', ['announcement' => $data->id]) . "' class='btn btn-soft-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='View'><i class='ri-eye-fill align-bottom'></i></a>
                        <a href='" . route('announcements.edit', ['announcement' => $data->id]) . "' class='btn btn-soft-success btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'><i class='ri-pencil-fill align-bottom'></i></a>
                        <button type='button' class='btn btn-soft-danger btn-sm remove-btn' id='" . $data->id . "' data-bs-toggle='tooltip' data-bs-placement='top' title='Remove'><i class='ri-delete-bin-5-fill align-bottom'></i></button>
                    </div>";

                    return $actions;
                })
                ->editColumn('section_id', function ($data) {
                    $section_id = $data->section_id;
                    $service_id = $data->service_id;

                    $section = Section::find($section_id);
                    $service = Service::find($service_id);

                    if (is_null($section) && is_null($service)) {
                        return 'Everyone';
                    }

                    if ($section === null) {
                        return $service->name;
                    }

                    if ($service === null) {
                        return $section->name;
                    }

                    return $section->name && $service->name ? $service->name . ' - ' . $section->name : 'N/A'; // Assuming 'name' is the field in Section model
                })
                ->rawColumns(['actions']) // Ensure that the HTML is rendered correctly
                ->make(true);
        }

        return view('pages.announcements.index', compact('announcements', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $id = $user->id;
        $data = $request->validate([
            'content' => 'required',
            'title' => 'required',
        ]);

        if ($request->sent_to_all === 'on') {
            Announcement::create(array_merge($data, [
                'user_id' => $id,
            ]));

            return redirect(route('announcements.index'))->with('success', 'Announcement created successfully');
        }

        Announcement::create(array_merge($data, [
            'user_id' => $id,
            'service_id' => $request->service,
            'section_id' => $request->section,
        ]));

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $announcement = Announcement::find($id);
        $title = $announcement->title;
        $content = $announcement->content;

        $service = Service::find($announcement->service_id);
        $section = Section::find($announcement->section_id);

        $created_at = Carbon::parse($announcement->created_at)->format('m-d-Y');
        $updated_at = Carbon::parse($announcement->updated_at)->format('m-d-Y');

        if (is_null($section) && is_null($service)) {
            $send_to = 'Everyone';

            return view('pages.announcement.show', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        if ($section === null) {
            $send_to = $service->name;
            return view('pages.announcement.show', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        if ($service === null) {
            $send_to = $section->name;
            return view('pages.announcement.show', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        $send_to = $service->name . ' - ' . $section->name;

        return view('pages.announcements.show', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $announcement = Announcement::find($id);
        $title = $announcement->title;
        $content = $announcement->content;

        $service = Service::find($announcement->service_id);
        $section = Section::find($announcement->section_id);

        $created_at = Carbon::parse($announcement->created_at)->format('m-d-Y');
        $updated_at = Carbon::parse($announcement->updated_at)->format('m-d-Y');

        if (is_null($section) && is_null($service)) {
            $send_to = 'Everyone';

            return view('pages.announcements.edit', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        if ($section === null) {
            $send_to = $service->name;
            return view('pages.announcements.edit', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        if ($service === null) {
            $send_to = $section->name;
            return view('pages.announcements.edit', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));
        }

        $send_to = $service->name . ' - ' . $section->name;

        return view('pages.announcements.edit', compact('title', 'announcement', 'content', 'service', 'section', 'send_to', 'created_at', 'updated_at'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'service_id' => $request->service,
            'section_id' => $request->section,
            'status' => $request->status,
        ];

        if ($request->send_to_all == 'on' || ($request->section === null && $request->service === null)) {
            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'service_id' => null,
                'section_id' => null,
                'status' => $request->status,
            ];
        }

        if (!$request->send_to_all || ($request->section === null && $request->service === null)) {
            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'service_id' => null,
                'section_id' => null,
                'status' => $request->status,
            ];
        }

        $announcement = Announcement::find($id);
        $save = $announcement->update($data);

        if (!$save) {
            return redirect()->route('announcements.edit', ['announcement' => $id])->with('error', 'Failed to update announcement');
        }

        return redirect()->route('announcements.edit', ['announcement' => $id])->with('success', 'Announcement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);

        $announcement = Announcement::findOrFail($id);
        $delete = $announcement->delete();

        if (!$delete) {
            return response()->json(['message' => 'Failed to delete announcement']);
        }

        return response()->json(['message' => 'Announcement deleted successfully']);
    }
}
