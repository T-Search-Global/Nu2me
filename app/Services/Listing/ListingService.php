<?php

namespace App\Services\Listing;

use App\Models\ListingModel;
use App\Models\ListingImageModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ListingService
{
    public function store(Request $request)
    {
        $listing = ListingModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'location' => $request->location,
            'feature_check' => $request->feature_check ?? 0,
        ]);

        if ($request->hasFile('img')) {
            $images = is_array($request->file('img')) ? $request->file('img') : [$request->file('img')];
            foreach ($images as $image) {
                $path = $image->store('images', 'public');
                ListingImageModel::create([
                    'listing_id' => $listing->id,
                    'image_path' => $path,
                ]);
            }
        }

        return $listing->load('images');
    }

    public function edit($id)
    {
        $listing = ListingModel::find($id);
        return $listing ? $listing->load('images') : null;
    }

    public function update(Request $request, $id)
    {
        $listing = ListingModel::find($id);
        if (!$listing) {
            return null;
        }

        $listing->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'location' => $request->location,
            'feature_check' => $request->feature_check ?? 0,
        ]);

        if ($request->hasFile('img')) {
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

        return $listing->load('images');
    }

    public function destroy($id)
    {
        $listing = ListingModel::find($id);
        if (!$listing) return false;

        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $listing->delete();
        return true;
    }
}
