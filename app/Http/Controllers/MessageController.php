<?php

namespace App\Http\Controllers;

use App\Services\Chat\MessageService;
use App\Models\MessageModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }



    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = $this->messageService->storeMessage(
            $conversationId,
            auth()->id(),
            $request->message
        );

        return response()->json($message);
    }

    public function show($conversationId)
    {
        $messages = $this->messageService->getMessages($conversationId);
        return response()->json($messages);
    }

}
