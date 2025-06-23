<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AnnouncementModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = AnnouncementModel::orderBy("created_at", "desc")->paginate(10);
        return view("Dashboard.announcement.index", compact("announcements"));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'img' => 'nullable|image|max:8048',
        ]);

        $imagePath = null;

        if ($request->hasFile('img')) {
            $image = $request->file('img'); // this is not an array
            $imagePath = $image->store('admin', 'public');
        }

        AnnouncementModel::create([
            'title' => $request->title,
            'message' => $request->message,
            'img' => $imagePath,
        ]);


        // OneSignal Push Notification
        $response =  Http::withHeaders([
            'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => env('ONESIGNAL_APP_ID'),
            'included_segments' => ['All'],
            'headings' => ['en' => $request->title],
            'contents' => ['en' => $request->message],
            'data' => ['type' => 'announcement'],
        ]);
        Log::info($response);
        return redirect()->back()->with('success', 'Announcement sent successfully!');
    }


    public function destroy($id)
    {
        $announcement = AnnouncementModel::findOrFail($id);
        $announcement->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }
}
