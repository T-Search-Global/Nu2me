<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Pin\PinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PinController extends Controller
{
    protected $pinService;

    public function __construct(PinService $pinService)
    {
        $this->pinService = $pinService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            'category' => 'required|string',
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pin = $this->pinService->store($request);

        return response()->json([
            'message' => 'Pin created successfully.',
            'pin' => $pin,
        ]);
    }
}
