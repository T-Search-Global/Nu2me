<?php

namespace App\Services\Listing;

use Stripe\Charge;
use Stripe\Stripe;
use App\Jobs\ListingJob;
use App\Models\RatingModel;
use App\Models\ListingModel;
use App\Models\PaymentModel;
use Illuminate\Http\Request;
use App\Models\ListingCharge;
use App\Models\ListingImageModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ListingService
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $setDays = 7;
        $setExpiryDate = now()->addDays($setDays)->format('Y-m-d');

        // Count current user's listings
        $listingCount = $user->listings()->count();
        $chargeAmount = 0;
        // Get admin-defined charges
        $charges = ListingCharge::first(); // Optional: use default values if null

        $featurePrice = $charges->feature_listing_amount ?? 10;
        $additionalPrice = $charges->additional_listing_amount ?? 5;

        if ($request->feature_check == 1) {
            $chargeAmount += $featurePrice;
        }
        if ($listingCount >= 1) {
            $chargeAmount += $additionalPrice;
        }

        // If any charge is applicable
        if ($chargeAmount != 0) {
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


                $paymentCount = PaymentModel::create([
                    'user_id' => $user->id,
                    'payment_type' => 'listing' ?? null,
                    'payment_gateway' => 'stripe',
                    'payment_status' => 'confirmed' ?? null,
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

        // Set expiry date if featured
        $expiryDate = null;

        if ($request->feature_check == 1) {
            $expiryDate = $setExpiryDate;
        }

        // Proceed with creating listing
        $listing = ListingModel::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'location' => $request->location,
            'dimensions' => $request->dimensions,
            'feature_check' => $request->feature_check ?? 0,
            'expiry_date' => $expiryDate,
            'sold' => $request->sold ?? 'no',
        ]);

        $listing->load('user', 'images');
        dispatch(new ListingJob($listing, $user));

        // ✅ Real-time notification to only that user
        if ($user->id) {
            $this->sendOneSignalNotification($user->id, 'Your listing "' . $listing->name . '" has been created successfully.', true);
        }


        // $paymentCount->listing_id = $listing->id;
        // $paymentCount->save();

        if (isset($paymentCount)) {
            $paymentCount->listing_id = $listing->id;
            $paymentCount->save();
        }


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


    public function getListing()
    {
        $listings = ListingModel::with(['images'])
            ->withAvg('ratings', 'rating')
            ->get();

        $listings = $listings->map(function ($listing) {
            // Convert to string using number_format or (string) casting
            $average = round($listing->ratings_avg_rating ?? 0, 1);
            $listing->average_rating = $average == 0 ? "0" : number_format($average, 1);

            unset($listing->ratings_avg_rating);
            unset($listing->ratings);

            return $listing;
        });

        return $listings;
    }




    public function listingDetail($id)
    {
        $listing = ListingModel::with(['images', 'user'])
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);

        // Average rating calculate and assign
        $average = $listing->average_rating = round($listing->ratings_avg_rating ?? 0, 1);
        $listing->average_rating = $average == 0 ? "0" : number_format($average, 1);
        // Remove ratings and ratings_avg_rating from response
        unset($listing->ratings_avg_rating);
        unset($listing->ratings);

        return $listing;
    }

    public function edit($id)
    {
        $listing = ListingModel::with(['images'])
            ->withAvg('ratings', 'rating')
            ->find($id);

        if (!$listing) {
            return null;
        }

        // Calculate average
        $average = round($listing->ratings_avg_rating ?? 0, 1);

        // Convert to string: if zero, show "0", else keep 1 decimal like "4.5"
        $listing->average_rating = $average == 0 ? "0" : number_format($average, 1);

        // Cleanup
        unset($listing->ratings_avg_rating);
        unset($listing->ratings);

        return $listing;
    }



    public function update(Request $request, $id)
    {
        $listing = ListingModel::find($id);
        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        // Update listing
        $listing->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'location' => $request->location,
            'dimensions' => $request->dimensions,
            // 'feature_check' => $request->feature_check ?? 0,
            'sold' => $request->sold ?? 'no',
            // 'expiry_date' => $request->expiry_date ?? null,
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


    public function storeRating($userId, $rating, $description, $listingId)
    {
        // Check if rating already exists for this user and listing
        $exists = RatingModel::where('user_id', $userId)
            ->where('listing_id', $listingId)
            ->exists();

        if ($exists) {
            throw new \Exception("You have already rated this listing.");
        }

        // If not exists, then create new rating
        return RatingModel::create([
            'user_id' => $userId,
            'rating' => (int) $rating,
            'description' => $description,
            'listing_id' => $listingId,
        ]);
    }



    public function sendOneSignalNotification($playerOrExternalId, $message, $useExternal = false)
    {
        $payload = [
            'app_id' => config('onesignal.app_id'),
            'contents' => ['en' => $message],
            'data' => ['message' => 'Listing Approved'],
        ];

        // 🔥 Cast to string to avoid OneSignal error
        $payload[$useExternal ? 'include_external_user_ids' : 'include_player_ids'] = [(string) $playerOrExternalId];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('onesignal.rest_api_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', $payload);

        if ($response->failed()) {
            Log::error('OneSignal notification failed:', [$response->body()]);
        }

        Log::info('OneSignal Response:', [$response->json()]);
    }


    public function searchListings(Request $request)
    {
        $query = ListingModel::with(['images'])
            ->whereNull('deleted_at');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('feature_check')) {
            $query->where('feature_check', $request->feature_check);
        }

        return $query->orderBy('id', 'desc')->get();
    }
}
