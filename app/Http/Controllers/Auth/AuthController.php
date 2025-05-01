<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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

    public function logout(Request $request)
    {
        return response()->json($this->authService->logout($request->user()));
    }

    public function sendOtpEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

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
