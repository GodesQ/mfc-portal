<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
  public function index(Request $request)
  {
    $startDate = $request->query('startDate');

    $eventsQuery = Event::query()->active(); // Only active events

    if ($startDate) {
      $eventsQuery->whereDate('start_date', '>=', $startDate);
    }

    $events = $eventsQuery
      ->orderBy('start_date', 'asc')
      ->get()
      ->map(function ($event) {
        return [
          'id' => $event->id,
          'type' => $event->type,
          'sections' => $event->sections(),
          'datetime' => $event->start_date . ' ' . $event->time,
          'location' => $event->location,
          'title' => $event->title,
        ];
      });

    return response()->json([
      'status' => 'success',
      'message' => 'Events retrieved successfully',
      'data' => $events
    ]);
  }
}
