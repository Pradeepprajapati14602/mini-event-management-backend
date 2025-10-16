<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Create a new event",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "location", "start_time", "end_time", "max_capacity"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="location", type="string"),
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="end_time", type="string", format="date-time"),
     *             @OA\Property(property="max_capacity", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created successfully"
     *     )
     * )
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'max_capacity' => 'required|integer|min:1',
        ]);

        // Convert start_time and end_time to UTC before storing
        $validated['start_time'] = Carbon::parse($validated['start_time'], 'Asia/Kolkata')->utc();
        $validated['end_time'] = Carbon::parse($validated['end_time'], 'Asia/Kolkata')->utc();

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="List all upcoming events",
     *     @OA\Response(
     *         response=200,
     *         description="List of events"
     *     )
     * )
     */
    public function list()
    {
        $events = Event::where('start_time', '>', now())->get()->map(function ($event) {
            $event->start_time = Carbon::parse($event->start_time)->setTimezone('Asia/Kolkata');
            $event->end_time = Carbon::parse($event->end_time)->setTimezone('Asia/Kolkata');
            return $event;
        });

        return response()->json($events);
    }

    public function addAttendee(Request $request, $event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:attendees,email,NULL,id,event_id,' . $event_id,
        ]);

        if ($event->attendees()->count() >= $event->max_capacity) {
            return response()->json(['error' => 'Event is fully booked'], 400);
        }

        $attendee = $event->attendees()->create($request->only('name', 'email'));

        return response()->json(['message' => 'Attendee added successfully', 'attendee' => $attendee], 200);
    }
}
