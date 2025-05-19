<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Public Events",
 *     description="Public access to view and register for events"
 * )
 */
class PublicEventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/public/events",
     *     summary="Get upcoming events for an organization",
     *     tags={"Public Events"},
     *     @OA\Response(
     *         response=200,
     *         description="List of upcoming events",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Event"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $organization = $request->attributes->get('organization');
        $events = $organization->events()->where('date', '>=', now())->get();
        return response()->json($events);
    }

    /**
     * @OA\Post(
     *     path="/api/public/register",
     *     summary="Register an attendee for an event",
     *     tags={"Public Events"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"event_id", "name", "email", "phone"},
     *             @OA\Property(property="event_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registered successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found in this organization",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Event not found in this organization.")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $organization = $request->attributes->get('organization');

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $event = $organization->events()->find($request->event_id);

        if (!$event) {
            return response()->json(['error' => 'Event not found in this organization.'], 404);
        }

        $event->attendees()->create($request->only(['name', 'email', 'phone']));

        return response()->json(['message' => 'Registered successfully.']);
    }
}
