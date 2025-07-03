<?php

namespace App\Http\Controllers;

use App\Models\ListingModel;
use Illuminate\Http\Request;
use App\Models\ListingCharge;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use App\Services\Listing\ListingService;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    protected $listingService;

    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    public function index()
    {
        $listings = ListingModel::with(['user', 'images'])->orderByDesc('id')->get();
        return view('Dashboard.listing.index', compact('listings'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'dimensions' => 'nullable|integer|min:0',
            'feature_check' => 'nullable|boolean',
            'img.*' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $listing = $this->listingService->store($request);

        return response()->json([
            'message' => 'Listing created successfully.',
            'listing' => $listing,
        ]);
    }


    public function getListing()
    {
        $listings = $this->listingService->getListing();
        return response()->json([
            'status' => 'true',
            'listings' => $listings,
        ]);
    }

    public function getListingDetail($id)
    {
        $listing = $this->listingService->listingDetail($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found.'], 404);
        }

        return response()->json([
            'status' => 'true',
            'listing' => $listing->makeHidden('user'),
            'user' => new UserResource($listing->user),
        ]);
    }


    public function edit($id)
    {
        $listing = $this->listingService->edit($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found.'], 404);
        }

        return response()->json([
            'status' => 'true',
            'listing' => $listing,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
            'dimensions' => 'required',
            // 'feature_check' => 'nullable|boolean',
            'img.*' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $listing = $this->listingService->update($request, $id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found.'], 404);
        }

        return response()->json([
            'message' => 'Listing updated successfully.',
            'listing' => $listing,
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->listingService->destroy($id);

        if (!$deleted) {
            return response()->json(['message' => 'Listing not found.'], 404);
        }

        return response()->json(['message' => 'Listing deleted successfully.']);
    }


    public function storeRating(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'listing_id' => 'required|exists:listings,id',
        ]);

        try {
            $rating = $this->listingService->storeRating(
                auth()->id(),
                $validated['rating'],
                $validated['description'],
                $validated['listing_id']
            );

            return response()->json([
                'message' => 'Rating saved successfully.',
                'rating' => $rating,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ], 400); // Changed from 500 to 400 for user input issue
        }
    }


    // use for admin
    public function listingCharges()
    {
        $charge = ListingCharge::first();

        return view('Dashboard.listingCharge.index', compact('charge'));
    }


    public function updateCharge(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:listing_charges,id',
            'feature_listing_amount' => 'required|numeric|min:0',
            'additional_listing_amount' => 'required|numeric|min:0',
        ]);

        ListingCharge::where('id', $request->id)->update([
            'feature_listing_amount' => $request->feature_listing_amount,
            'additional_listing_amount' => $request->additional_listing_amount,
        ]);

        return redirect()->back()->with('success', 'Charges updated successfully.');
    }

    function sendNotification($playerId, $title, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic YOUR_ONESIGNAL_REST_API_KEY',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => 'b600c223-1399-4922-ab6a-0f95bd2bc420',
            'include_player_ids' => [$playerId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ]);

        return $response->json();
    }





    public function listingSearch(Request $request)
    {
        $listings = $this->listingService->searchListings($request);
        return response()->json($listings);
    }

// usign in app purchase listing maeke it feature
    public function markAsFeatured(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $listing = ListingModel::find($request->listing_id);
        $listing->feature_check = true;
        $listing->save();

        return response()->json([
            'message' => 'Listing marked as featured successfully.',
            'listing' => $listing
        ]);
    }
}
