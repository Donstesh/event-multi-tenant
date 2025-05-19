<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organization_id', auth()->user()->organization_id)->get();
        return response()->json($events);
    }

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

    public function show(Event $event)
    {
        $this->authorizeAccess($event);
        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeAccess($event);

        $event->update($request->only([
            'title', 'description', 'venue', 'date', 'price', 'max_attendees'
        ]));

        return response()->json($event);
    }

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

