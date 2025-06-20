<?php

namespace App\Services;

use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use App\Jobs\SendRegisterJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register($data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'country' => $data['country'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        dispatch(new SendRegisterJob($user));

        return [
            'status' => true,
            'message' => 'User registered Successfully',
            'token' => $token,
        ];
    }

    public function login($credentials)
    {
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'message' => 'Login Successfully',
        ];
    }

    public function logout($user)
    {
        $user->tokens()->delete();
        return ['message' => 'logged out Successfully'];
    }

    public function sendOtpEmail($email)
    {
        $user = User::where('email', $email)->first();
        dispatch(new SendEmailJob($user));

        return ['message' => "OTP sent to {$email}"];
    }

    public function verifyOtp($email, $code)
    {
        $otpRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$otpRecord || !Hash::check($code, $otpRecord->token)) {
            return ['message' => 'Invalid OTP code', 'status' => false];
        }

        return ['message' => 'OTP matched!', 'status' => true];
    }

    public function updatePassword($email, $otp, $newPassword)
    {
        $user = User::where('email', $email)->first();

        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetRecord || !Hash::check($otp, $resetRecord->token)) {
            return ['message' => 'Invalid or expired OTP', 'status' => false];
        }

        $user->update(['password' => Hash::make($newPassword)]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return ['message' => 'Password changed successfully', 'user' => new UserResource($user)];
    }

    public function updateUser($user, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return ['message' => 'User updated successfully', 'user' => $user];
    }

    public function getUser($user)
    {
        return ['status' => true, 'user' => $user];
    }
}
