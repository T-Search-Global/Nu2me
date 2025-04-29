<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get validated data
        $validated = $validator->validated();


        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        // dd($request->all());
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    // public function sendResetLinkEmail(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     $user = \App\Models\User::where('email', $request->email)->first();

    //     // Generate 6 digit OTP
    //     $otp = rand(100000, 999999);

    //     // Save OTP in password_reset_tokens table
    //     DB::table('password_reset_tokens')->updateOrInsert(
    //         ['email' => $user->email],
    //         [
    //             'email' => $user->email,
    //             'token' => bcrypt($otp),
    //             'created_at' => Carbon::now()
    //         ]
    //     );

    //     // Send OTP email
    //     $user->notify(new ResetPasswordNotification($otp));

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'OTP sent to your email successfully!',
    //     ]);
    // }


    public function sendOtpEmail(Request $request){

        $user = User::where('email', $request->email)->first();
        dispatch(new SendEmailJob($user));

        return response()->json([
            'message' => 'OTP send to an email ' . $request->email,
        ], 200);

    }


 // otp verify
 public function forgotPasswordOtp(Request $request)
 {
    // dd($request->all());
    $request->validate([
         'email' => 'required|email|exists:users,email',
         'code' => 'required|string|max:4',
     ]);

     $user = User::where('email', $request->email)->first();

     if (!$user) {
         return response()->json([
             'message' => 'Invalid email',
         ], 401);
     }

     $otpRecord = DB::table('password_reset_tokens')
         ->where('email', $request->email)
         ->first();

     if (!$otpRecord) {
         return response()->json([
             'message' => 'Invalid OTP code',
         ], 401);
     }

     if (Hash::check($request->code, $otpRecord->token)) {
         return response()->json([
             'message' => 'OTP matched!',
         ], 200);
     }

     return response()->json([
         'message' => 'Invalid OTP code',
     ], 401);
 }

 public function updatePassword(Request $request)
 {
     $request->validate([
         'email' => 'required|email|exists:users,email',
         'otp' => 'required|string',         
         'new_password' => 'required|min:4',
     ]);

     $user = User::where('email', $request->email)->first();

     if (!$user) {
         return response()->json([
             'message' => 'User not found',
         ], 404);
     }

     // Check OTP from password_reset_tokens table
     $resetRecord = DB::table('password_reset_tokens')
         ->where('email', $request->email)
         ->first();

     if (!$resetRecord || !Hash::check($request->otp, $resetRecord->token)) {
         return response()->json([
             'message' => 'Invalid or expired OTP',
         ], 401);
     }

     // Update Password
     $user->update([
         'password' => Hash::make($request->new_password),
     ]);

     // Delete OTP after success
     DB::table('password_reset_tokens')->where('email', $request->email)->delete();

     return response()->json([
        'message' => 'Password changed successfully',
        'data' => new UserResource($user),
    ], 200);
 }



}
