<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function markAsPaid(Request $request)
    {
        $request->validate([
            'payment_status' => 'required|in:success',
        ]);

        $user = auth()->user();

        $user->is_paid = true;
        $user->save();

        return response()->json([
            'message' => 'User marked as paid successfully.',
            'is_paid' => $user->is_paid,
            'userDetails' => new UserResource($user),
        ]);
    }


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:7,15|unique:users,phone',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'img' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json($this->authService->register($validator->validated()), 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        return response()->json($this->authService->login($request->only('email', 'password')));
    }





    //         public function loginWithGoogle(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required|string'
    //     ]);
    //     // dd($request);

    //     try {
    //         // // Step 1: Exchange code for access + refresh token
    //         // $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
    //         //     'grant_type' => 'authorization_code',
    //         //     'client_id' => config('services.google.client_id'),
    //         //     'client_secret' => config('services.google.client_secret'),
    //         //     'redirect_uri' => config('services.google.redirect'),
    //         //     'code' => $request->code,
    //         // ]);


    //         // if (!$response->successful()) {
    //         //     return response()->json(['error' => 'Failed to get Google token'], 400);
    //         // }

    //         // $tokenData = $response->json();

    //         // Step 2: Get user info from Google
    //         $googleUser = Socialite::driver('google')->stateless()
    //             ->userFromToken($tokenData['access_token']);

    //         // Step 3: Create or update user
    //         $user = User::updateOrCreate(
    //             ['email' => $googleUser->getEmail()],
    //             [
    //                 'first_name' => $googleUser->getName() ?? 'N/A',
    //                 'last_name' => $googleUser->getNickname() ?? 'N/A',
    //                 'google_access_token' => $tokenData['access_token'],
    //                 'google_refresh_token' => $tokenData['refresh_token'] ?? $user->google_refresh_token ?? null,
    //                 'google_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
    //                 'password' => Hash::make(Str::random(24)),
    //             ]
    //         );

    //         // Step 4: Laravel token for mobile app
    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'Google login successful',
    //             'user' => $user,
    //             'token' => $token
    //         ]);

    //     } catch (\Exception $e) {
    //         dd(''. $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }





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
                    'first_name' => $fbUser->getName(),
                    'email' => $fbUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'img' => null,
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




    public function logout(Request $request)
    {
        return response()->json($this->authService->logout($request->user()));
    }

    public function sendOtpEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        return response()->json($this->authService->sendOtpEmail($request->email));
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        return response()->json($this->authService->sendOtpEmail($request->email));
    }

    public function forgotPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|max:6',
        ]);

        $response = $this->authService->verifyOtp($request->email, $request->code);

        return response()->json(['message' => $response['message']], $response['status'] ? 200 : 401);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
            'new_password' => 'required|min:6',
        ]);

        $result = $this->authService->updatePassword(
            $request->email,
            $request->otp,
            $request->new_password
        );

        return response()->json($result, isset($result['status']) && $result['status'] === false ? 401 : 200);
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|digits_between:7,15|unique:users,phone,' . $user->id,
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return response()->json($this->authService->updateUser($user, $validator->validated()));
    }

    public function editUser()
    {
        return response()->json($this->authService->getUser(Auth::user()));
    }
}
