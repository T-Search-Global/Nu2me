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
        ]);

        $user->createToken('auth_token')->plainTextToken;

        return [
            'message' => 'Pin created successfully',
            'pin' => $pin,
        ];
    }




}
