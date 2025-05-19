<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class PublicEventController extends Controller{
        public function index(Request $request)
    {
        $organization = $request->attributes->get('organization');
        $events = $organization->events()->where('date', '>=', now())->get();
        return response()->json($events);
    }

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
