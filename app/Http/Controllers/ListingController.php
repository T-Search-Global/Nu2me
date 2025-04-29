<?php

namespace App\Http\Controllers;

use App\Models\ListingImageModel;
use App\Models\ListingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
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
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $listing = ListingModel::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'price' => $request->input('price'),
            'location' => $request->input('location'),
            'feature_check' => $request->input('feature_check', 0),
        ]);

        if ($request->hasFile('img')) {
            $images = $request->file('img');

            if (!is_array($images)) {
                $images = [$images];
            }

            foreach ($images as $image) {
                $path = $image->store('images', 'public');
                ListingImageModel::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Listing created successfully.',
            'listing' => $listing->load('images'),
        ]);
    }


   public function edit($id)
   {
    $listing = ListingModel::find($id);

    if (!$listing) {
        return response()->json([
            'message' => 'Listing not found.'
        ], 404);
    }


    return response()->json([
        'status' => 'true',
        'listing' => $listing->load('images'),
    ]);
   }


   public function update(Request $request, $id)
{
    // Find the listing
    $listing = ListingModel::find($id);

    if (!$listing) {
        return response()->json([
            'message' => 'Listing not found.'
        ], 404);
    }

    // Validate request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'price' => 'required|numeric',
        'location' => 'required|string',
        'feature_check' => 'nullable|boolean',
        'img.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Update listing
    $listing->update([
        'name' => $request->name,
        'description' => $request->description,
        'category' => $request->category,
        'price' => $request->price,
        'location' => $request->location,
        'feature_check' => $request->feature_check ?? 0,
    ]);

    // Handle image upload (optional)
    if ($request->hasFile('img')) {
        // Optionally delete old images if needed
        foreach ($listing->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        foreach ($request->file('img') as $image) {
            $path = $image->store('images', 'public');
            ListingImageModel::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
            ]);
        }
    }

    return response()->json([
        'message' => 'Listing updated successfully.',
        'listing' => $listing->load('images'),
    ]);
    }

    public function destroy($id)
    {
        // Find the listing
        $listing = ListingModel::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found.'
            ], 404);
        }

        // Delete associated images (optional but recommended)
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete the listing
        $listing->delete();

        return response()->json([
            'message' => 'Listing deleted successfully.'
        ]);
    }

}
