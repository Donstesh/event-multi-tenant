<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request, $org)
    {
        $organization = Organization::where('slug', $org)->firstOrFail();
        $events = $organization->events()->where('date', '>=', now())->get();

        return response()->json($events);
    }

    public function register(Request $request, $org)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $event = Event::findOrFail($request->event_id);

        if ($event->organization->slug !== $org) {
            return response()->json(['error' => 'Unauthorized event.'], 403);
        }

        Attendee::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'Registered successfully.']);
    }
}
