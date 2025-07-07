<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConversationModel;
use App\Http\Controllers\Controller;
use App\Services\Chat\ConversationService;

class ConversationController extends Controller
{
    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'listing_id' => 'nullable',
        ]);

        $conversation = $this->conversationService->createOrGetConversation(
            auth()->id(),
            $request->receiver_id,
            $request->listing_id
        );

        return response()->json($conversation);
    }

    public function show()
    {
        $user = auth()->user();

        $conversations = $this->conversationService->getUserConversations($user->id);

        return response()->json($conversations);
    }
}
