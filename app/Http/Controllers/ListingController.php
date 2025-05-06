<?php

namespace App\Http\Controllers;

use App\Services\Listing\ListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    protected $listingService;

    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string',
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
            'feature_check' => 'nullable|boolean',
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
}
