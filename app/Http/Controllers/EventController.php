<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id;
        return Event::where('organization_id', $orgId)->get();
    }

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

    public function show(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        return response()->json($event);
    }

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

    public function destroy(Request $request, $id)
    {
        $event = Event::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $event->delete();

        return response()->json(['message' => 'Event deleted']);
    }
}
