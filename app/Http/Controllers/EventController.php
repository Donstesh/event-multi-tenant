<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="List all events for the authenticated user's organization",
     *     tags={"Events"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of events"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id;
        return Event::where('organization_id', $orgId)->get();
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Create a new event",
     *     tags={"Events"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "venue", "date", "price", "max_attendees"},
     *             @OA\Property(property="title", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="venue", type="string", maxLength=255),
     *             @OA\Property(property="date", type="string", format="date"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="max_attendees", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1',
        ]);

        $data['organization_id'] = $request->user()->organization_id;

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Get a specific event by ID",
     *     tags={"Events"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        return response()->json($event);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     summary="Update an event",
     *     tags={"Events"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="venue", type="string", maxLength=255),
     *             @OA\Property(property="date", type="string", format="date"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="max_attendees", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'price' => 'sometimes|numeric|min:0',
            'max_attendees' => 'sometimes|integer|min:1',
        ]);

        $event->update($data);

        return response()->json($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Delete an event",
     *     tags={"Events"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the event to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }
}
