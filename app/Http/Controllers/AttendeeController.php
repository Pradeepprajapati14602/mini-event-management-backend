<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AttendeeController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/events/{event_id}/register",
 *     summary="Register an attendee for an event",
 *     description="Registers an attendee for a specific event, prevents overbooking and duplicate registrations.",
 *     tags={"Attendees"},
 *     @OA\Parameter(
 *         name="event_id",
 *         in="path",
 *         required=true,
 *         description="ID of the event",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Attendee registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Attendee registered successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error or overbooking",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Event is fully booked")
 *         )
 *     )
 * )
 */

    // public function register(Request $request, $eventId)
    // {
    //     $event = Event::findOrFail($eventId);

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //     ]);

    //     try {
    //         $attendee = $this->attendeeService->registerAttendee($event, $validated);
    //         return response()->json($attendee, 201);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
    // }

    // public function register(Request $request, $event_id)
    // {
    //     $event = Event::find($event_id);

    //     if (!$event) {
    //         return response()->json(['error' => 'Event not found'], 404);
    //     }

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //     ]);

    //     // Check for duplicate registration
    //     if ($event->attendees()->where('email', $request->email)->exists()) {
    //         return response()->json(['error' => 'Attendee with this email is already registered'], 400);
    //     }

    //     // Check for overbooking
    //     if ($event->attendees()->count() >= $event->max_capacity) {
    //         return response()->json(['error' => 'Event is fully booked'], 400);
    //     }

    //     $attendee = $event->attendees()->create($request->only('name', 'email'));

    //     return response()->json(['message' => 'Attendee registered successfully', 'attendee' => $attendee], 200);
    // }

    public function register(Request $request, $event_id)
    {
        try {
            // 🛡️ Step 1: Event ID ko validate karo
            if (!ctype_digit((string) $event_id)) {
                return response()->json(['error' => 'Invalid event ID'], 400);
            }

            $event = Event::find((int) $event_id);

            if (!$event) {
                return response()->json(['error' => 'Event not found'], 404);
            }

            // 📝 Step 2: Input validation
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
            ]);

            // 🧍 Step 3: Duplicate registration check
            if ($event->attendees()->where('email', $request->email)->exists()) {
                return response()->json(['error' => 'Attendee with this email is already registered'], 400);
            }

            // 🚫 Step 4: Overbooking check
            if ($event->attendees()->count() >= $event->max_capacity) {
                return response()->json(['error' => 'Event is fully booked'], 400);
            }

            // ✅ Step 5: Create attendee
            $attendee = $event->attendees()->create($request->only('name', 'email'));

            return response()->json([
                'message' => 'Attendee registered successfully',
                'attendee' => $attendee
            ], 200);

        } catch (\Exception $e) {
            // 🐞 Optional: catch any unexpected errors
            return response()->json([
                'error' => 'Something went wrong. Please try again later.',
                'details' => $e->getMessage() // ❌ remove in production
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/events/{event_id}/attendees",
     *     summary="List all attendees for an event",
     *     description="Returns all registered attendees for a specific event.",
     *     tags={"Attendees"},
     *     @OA\Parameter(
     *         name="event_id",
     *         in="path",
     *         required=true,
     *         description="ID of the event",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of attendees",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
     *             ))
     *         )
     *     )
     * )
     */
    public function list($eventId)
    {
        $event = Event::findOrFail($eventId);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $attendees = $event->attendees()->paginate(10); // Paginate with 10 attendees per page

        return response()->json($attendees);
    }
}
