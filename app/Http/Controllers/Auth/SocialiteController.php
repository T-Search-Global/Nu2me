<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function loginWithGoogle(Request $request)
    {

        $request->validate([
            'token' => 'required|string'
        ]);


        try {
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->token);
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'first_name' => $googleUser->getName() ?? "N/a",
                    'last_name' => $googleUser->getNickname() ?? 'N/A',
                    'email' => $googleUser->getEmail(),
                    'phone' => "N/a",
                    'password' => Hash::make(Str::random(24)),
                    'img' => $googleUser->getAvatar(), // you can save this
                    'city' => null ?? "N/a",
                    'country' => null ?? "N/a",
                    ]
                );

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Logged in with Google successfully',
                    'user' => new UserResource($user),
                    'token' => $token
                ]);

            } catch (\Exception $e) {
                dd($e->getMessage());
                return response()->json(['error' => 'Invalid Google token'], 401);
        }
    }



    public function refreshGoogleToken(User $user)
{
    if (!$user->google_refresh_token) {
        return response()->json(['error' => 'No refresh token stored.'], 403);
    }

    $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
        'grant_type' => 'refresh_token',
        'client_id' => config('services.google.client_id'),
        'client_secret' => config('services.google.client_secret'),
        'refresh_token' => $user->google_refresh_token,
    ]);

    if (!$response->successful()) {
        return response()->json(['error' => 'Failed to refresh token'], 400);
    }

    $tokenData = $response->json();

    $user->update([
        'google_access_token' => $tokenData['access_token'],
        'google_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
    ]);

    return response()->json(['message' => 'Access token refreshed']);
}




public function loginWithFacebook(Request $request)
{
    $request->validate([
        'token' => 'required|string'
    ]);

    try {
        $fbUser = Socialite::driver('facebook')->stateless()->userFromToken($request->token);

        $user = User::updateOrCreate(
            ['email' => $fbUser->getEmail()],
            [
                'first_name' => $fbUser->getName() ?? "N/A",
                'last_name' => $fbUser->getNickname() ?? 'N/A',
                'email' => $fbUser->getEmail(),
                'phone' => "N/A",
                'password' => Hash::make(Str::random(24)),
                'img' => $fbUser->getAvatar(),
                'city' => "N/A",
                'country' => "N/A"
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in with Facebook successfully',
            'user' => new UserResource($user),
            'token' => $token
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid Facebook token'], 401);
    }
}

}
