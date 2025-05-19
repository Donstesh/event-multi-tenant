<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function index()
    {
        $organizationId = auth()->user()->organization_id;

        $attendees = Attendee::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->get();

        return response()->json($attendees);
    }
}

