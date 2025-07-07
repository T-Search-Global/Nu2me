<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->get();
        return view('Dashboard.event.create', compact('events'));
    }


    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'user_id' => 'required|exists:users,id',
    //         'image' => 'nullable|image|max:8048',
    //     ]);


    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image'); // this is not an array
    //         $imagePath = $image->store('admin', 'public');
    //     }

    //     $event = Event::create([
    //         'name' => $request->name,
    //         'image' => $imagePath,
    //     ]);

    //     // ✅ Send Push Notification to All via OneSignal
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Basic ' . config('onesignal.rest_api_key'),
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ])->post('https://onesignal.com/api/v1/notifications', [
    //         'app_id' => config('onesignal.app_id'),
    //         'included_segments' => ['All'],
    //         'headings' => ['en' => 'New Event'],
    //         'contents' => ['en' => $event->name],
    //         'data' => [
    //             'type' => 'event',
    //             'event_id' => $event->id
    //         ],
    //     ]);

    //     Log::info('OneSignal Event Push:', [$response->json()]);

    //     return redirect()->route('admin.events.index')->with('success', 'Event created and notification sent.');
    // }



    public function approve(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->approve = true;
        $event->save();

        return redirect()->back()->with('success', 'Event approved successfully.');
    }

    // public function destroy($id)
    // {
    //     $event = Event::findOrFail($id);
    //     if (!$event) {
    //         return redirect()->back()->with('error', 'Event Not Find');
    //     }
    //     $event->delete();
    //     return redirect()->back()->with('success', 'Event Deleted Successfully');
    // }



    // for user  events
    public function events()
    {
        $events = Event::where('is_event_paid', 0) // unpaid
            ->where('approve', 1) // approved
            ->with('user')        // eager load user if needed
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'event_name' => $event->name,
                    'description' => $event->description,
                    'image' => $event->image ? asset('storage/' . $event->image) : null,
                    'approve' => (bool) $event->approve,
                    'is_event_paid' => (bool) $event->is_event_paid,
                    'date' => $event->date ?? null,
                    'user' => [
                        'id' => $event->user->id ?? null,
                        'name' => $event->user->first_name ?? null,
                        'email' => $event->user->email ?? null,
                    ],
                ];
            });

        return response()->json($events);
    }



    public function eventCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|max:8048',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('user', 'public');
        }

        $event = Event::create([
            'name' => $request->name,
            'image' => $imagePath,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'is_event_paid' => false,
            'approve' => false,
        ]);

        // Push Notification via OneSignal
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
                'event_id' => $event->id,
            ],
        ]);

        Log::info('OneSignal Event Push:', [$response->json()]);
        return response()->json($event);
    }


    public function markEventPaid(Request $request, $id)
    {
        $user = auth()->user();

        $event = Event::where('id', $id)->where('user_id', $user->id)->first();

        if (!$event) {
            return response()->json([
                'message' => 'Event not found or unauthorized.'
            ], 404);
        }

        $event->is_event_paid = true;
        $event->save();

        return response()->json([
            'message' => 'Event marked as paid successfully.',
            'event' => $event,
        ]);
    }
}
