<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Tithe;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $user = auth()->user();
        $recent_event_registrations = EventRegistration::latest()->with('event','user')->get();
        $upcoming_events = Event::where('start_date', '>=', date('Y-m-d'))
                                ->orderBy('start_date', 'asc')
                                ->limit(5)
                                ->get();
        
        $latest_tithes = Tithe::where('status', 'paid')->latest()->limit(5)->get();
        $announcements = Announcement::latest()->limit(5)->get();
        return view('pages.dashboards.index', compact('recent_event_registrations', 'upcoming_events', 'latest_tithes', 'announcements'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
