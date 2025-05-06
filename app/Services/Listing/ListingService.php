<?php

namespace App\Services\Listing;

use App\Models\ListingImageModel;
use App\Models\ListingModel;
use App\Models\PaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stripe\Charge;
use Stripe\Stripe;

class ListingService
{
    // public function store(Request $request)
    // {
    //     $listing = ListingModel::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'category' => $request->category,
    //         'price' => $request->price,
    //         'location' => $request->location,
    //         'feature_check' => $request->feature_check ?? 0,
    //     ]);

    //     if ($request->hasFile('img')) {
    //         $images = is_array($request->file('img')) ? $request->file('img') : [$request->file('img')];
    //         foreach ($images as $image) {
    //             $path = $image->store('images', 'public');
    //             ListingImageModel::create([
    //                 'listing_id' => $listing->id,
    //                 'image_path' => $path,
    //             ]);
    //         }
    //     }

    //     return $listing->load('images');
    // }





    public function store(Request $request)
    {
        $user = Auth::user();

        // Count current user's listings
        $listingCount = $user->listings()->count();
        $chargeAmount = 0;

        // FEATURE CHECK: $10 always
        if ($request->feature_check == 1) {
            $chargeAmount = 10;
        }
        // SECOND LISTING: $5
        if ($listingCount > 1) {
            $chargeAmount += 5;
        }


        // If any charge is applicable
        if ($chargeAmount !=0) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
                // production
                // Charge::create([
                //     'amount' => $chargeAmount * 100, // in cents
                //     'currency' => 'usd',
                //      "source" => $request->stripeToken,
                //     'description' => 'Listing fee',
                // ]);

                // testing for postman
                $charge =   Charge::create([
                    'amount' => $chargeAmount * 100,
                    'currency' => 'usd',
                    'source' => 'tok_visa', // this is a test token from Stripe
                    'description' => 'Listing fee',
                ]);


                $paymentCount= PaymentModel::create([
                    'user_id' => $user->id,
                    'payment_type' =>'listing'?? null,
                    'payment_method' => 'stripe',
                    'transaction_id' => $charge->id,
                    'amount' => $charge->amount / 100, // Convert cents to dollars
                    'currency' => $charge->currency,
                    'description' => $charge->description,
                    'paid_at' => now(),
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 400);
            }
        }

        // Proceed with creating listing
        $listing = ListingModel::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'location' => $request->location,
            'feature_check' => $request->feature_check ?? 0,
        ]);
        $paymentCount->listing_id = $listing->id;
        $paymentCount->save();
        // Handle image uploads
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

    // public function update(Request $request, $id)
    // {
    //     $listing = ListingModel::find($id);
    //     if (!$listing) {
    //         return null;
    //     }

    //     if($request->feature_check == 1){
    //         $payments = PaymentModel::where('listing_id', $id)->first();
    //         if($payments){
    //             $listing->update([
    //                 'name' => $request->name,
    //                 'description' => $request->description,
    //                 'category' => $request->category,
    //                 'price' => $request->price,
    //                 'location' => $request->location,
    //                 'feature_check' => $request->feature_check ?? 0,
    //             ]);

    //             if ($request->hasFile('img')) {
    //                 foreach ($listing->images as $img) {
    //                     Storage::disk('public')->delete($img->image_path);
    //                     $img->delete();
    //                 }

    //                 foreach ($request->file('img') as $image) {
    //                     $path = $image->store('images', 'public');
    //                     ListingImageModel::create([
    //                         'listing_id' => $listing->id,
    //                         'image_path' => $path,
    //                     ]);
    //                 }
    //             }
    //         }else{

    //         }
    //     }

    //     $listing->update([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'category' => $request->category,
    //         'price' => $request->price,
    //         'location' => $request->location,
    //         'feature_check' => $request->feature_check ?? 0,
    //     ]);

    //     if ($request->hasFile('img')) {
    //         foreach ($listing->images as $img) {
    //             Storage::disk('public')->delete($img->image_path);
    //             $img->delete();
    //         }

    //         foreach ($request->file('img') as $image) {
    //             $path = $image->store('images', 'public');
    //             ListingImageModel::create([
    //                 'listing_id' => $listing->id,
    //                 'image_path' => $path,
    //             ]);
    //         }
    //     }

    //     return $listing->load('images');
    // }



    public function update(Request $request, $id)
{
    $listing = ListingModel::find($id);
    if (!$listing) {
        return response()->json(['message' => 'Listing not found'], 404);
    }

    $wasFeatured = $listing->feature_check;
    $willBeFeatured = $request->feature_check == 1;

    if ($willBeFeatured && !$wasFeatured) {
        // Check if payment already exists
        $paymentExists = PaymentModel::where('listing_id', $id)
            ->exists();

        if (!$paymentExists) {
            // for production this if
            // if (!$request->stripe_token) {
            //     return response()->json(['message' => 'Stripe token is required.'], 400);
            // }

            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $charge = Charge::create([
                    'amount' => 1000, // $10 in cents
                    'currency' => 'usd',
                    'description' => 'Feature Listing Payment updated',
                    // 'source' => $request->stripe_token, for production
                    'source' => 'tok_visa',
                ]);

                // Store payment record
                PaymentModel::create([
                    'user_id' => Auth::id(),
                    'listing_id' => $listing->id,
                    'amount' =>  $charge->amount / 100,
                    'type' => 'feature',
                    'stripe_payment_id' => $charge->id,
                    'payment_type' =>'listing'?? null,
                    'payment_method' => 'stripe',
                    'currency' => $charge->currency,
                    'description' => $charge->description,
                    'paid_at' => now(),
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 500);
            }
        }
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

        
        // Image update logic
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
