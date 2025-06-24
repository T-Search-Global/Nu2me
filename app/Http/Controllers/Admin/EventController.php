<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('Dashboard.event.create', compact('events'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:8048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image'); // this is not an array
            $imagePath = $image->store('admin', 'public');
        }

        $event = Event::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);

        // ✅ Send Push Notification to All via OneSignal
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('onesignal.rest_api_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('onesignal.app_id'),
            'included_segments' => ['All'],
            'headings' => ['en' => 'New Event'],
            'contents' => ['en' => $event->name],
            'data' => [
                'type' => 'event',
                'event_id' => $event->id
            ],
        ]);

        Log::info('OneSignal Event Push:', [$response->json()]);

        return redirect()->route('admin.events.index')->with('success', 'Event created and notification sent.');
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if (!$event) {
            return redirect()->back()->with('error', 'Event Not Find');
        }
        $event->delete();
        return redirect()->back()->with('success', 'Event Deleted Successfully');
    }



    // for user shwo events
    public function events(){

     $events = Event::all()->map(function ($event) {
        return [
            'id' => $event->id,
            'name' => $event->name,
            'image' => $event->image ? asset('storage/' . $event->image) : null,
        ];
    });

    return response()->json($events);

    }
}
