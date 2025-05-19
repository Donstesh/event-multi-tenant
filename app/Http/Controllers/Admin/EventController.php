<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Events",
 *     description="Manage events for the admin's organization"
 * )
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/events",
     *     tags={"Events"},
     *     summary="List all events",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of events",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Event"))
     *     )
     * )
     */
    public function index()
    {
        $events = Event::where('organization_id', auth()->user()->organization_id)->get();
        return response()->json($events);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/events",
     *     tags={"Events"},
     *     summary="Create a new event",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "venue", "date", "price", "max_attendees"},
     *             @OA\Property(property="title", type="string", example="Tech Meetup"),
     *             @OA\Property(property="description", type="string", example="A gathering of tech enthusiasts."),
     *             @OA\Property(property="venue", type="string", example="Downtown Hall"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-08-01"),
     *             @OA\Property(property="price", type="number", format="float", example=20.5),
     *             @OA\Property(property="max_attendees", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'venue' => 'required|string',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'max_attendees' => 'required|integer',
        ]);

        $event = Event::create([
            ...$validated,
            'organization_id' => auth()->user()->organization_id,
        ]);

        return response()->json($event, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/events/{id}",
     *     tags={"Events"},
     *     summary="Get a single event",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event details",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function show(Event $event)
    {
        $this->authorizeAccess($event);
        return response()->json($event);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/events/{id}",
     *     tags={"Events"},
     *     summary="Update an event",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Tech Meetup"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="venue", type="string", example="Updated Venue"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-09-01"),
     *             @OA\Property(property="price", type="number", format="float", example=25.0),
     *             @OA\Property(property="max_attendees", type="integer", example=150)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event updated",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function update(Request $request, Event $event)
    {
        $this->authorizeAccess($event);

        $event->update($request->only([
            'title', 'description', 'venue', 'date', 'price', 'max_attendees'
        ]));

        return response()->json($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/events/{id}",
     *     tags={"Events"},
     *     summary="Delete an event",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Event deleted")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy(Event $event)
    {
        $this->authorizeAccess($event);
        $event->delete();
        return response()->json(['message' => 'Event deleted']);
    }

    private function authorizeAccess(Event $event)
    {
        abort_unless($event->organization_id === auth()->user()->organization_id, 403);
    }
}
