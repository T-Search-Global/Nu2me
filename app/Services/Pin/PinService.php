<?php

namespace App\Services\Pin;

use App\Models\CreatePinModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinService
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $pin = CreatePinModel::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $user->createToken('auth_token')->plainTextToken;

        return $pin;
    }


    public function show()
    {
        $pins = CreatePinModel::with('user')
            ->select(['id', 'name', 'description', 'category', 'location', 'user_id', 'latitude', 'longitude'])
            ->get()
            ->map(function ($pin) {
                return [
                    'id' => $pin->id,
                    'title' => $pin->name, // renamed here
                    'description' => $pin->description,
                    'category' => $pin->category,
                    'location' => $pin->location,
                    'user_id' => $pin->user_id,
                    'latitude' => (float) $pin->latitude,
                    'longitude' => (float) $pin->longitude,

                    'user' => [
                        'id' => $pin->user->id ?? null,
                        'first_name' => $pin->user->first_name ?? null,
                        'last_name' => $pin->user->last_name ?? null,
                        'email' => $pin->user->email ?? null,
                        'email_verified_at' => $pin->user->email_verified_at ?? null,
                        'phone' => $pin->user->phone ?? null,
                        'city' => $pin->user->city ?? null,
                        'country' => $pin->user->country ?? null,
                        'img' => $pin->user->img ?? null,
                        'deleted_at' => $pin->user->deleted_at ?? null,
                        'is_paid' => $pin->user->is_paid ?? null,
                        'img_url' => $pin->user->img_url ?? null,
                    ]
                ];
            });

        return $pins;
    }
}
