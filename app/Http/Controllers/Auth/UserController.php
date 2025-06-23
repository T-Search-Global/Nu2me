<?php

namespace App\Http\Controllers\Auth;

use \Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|digits_between:7,15|unique:users,phone,' . $user->id,
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            // 'password' => 'sometimes|string|min:6|confirmed',
            'img' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload
        if ($request->hasFile('img')) {
            if ($user->img && \Storage::exists('public/user/img/' . $user->img)) {
                \Storage::delete('public/user/img/' . $user->img);
            }

            $image = $request->file('img');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/user/img', $imageName);
            $user->img = $imageName;
        }

        // Update other profile fields
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->email = $request->email ?? $user->email;
        $user->phone = $request->phone ?? $user->phone;
        $user->city = $request->city ?? $user->city;
        $user->country = $request->country ?? $user->country;

        // Handle password update
        // if ($request->filled('password')) {
        //     $user->password = bcrypt($request->password);
        // }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user)
        ], 200);
    }


}
