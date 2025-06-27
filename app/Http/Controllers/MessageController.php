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
            'message' => 'nullable|string',
             'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,mp4|max:10240'
        ]);

        $message = $this->messageService->storeMessage(
            $conversationId,
             auth()->id(),
            $request->message,
             $request->file('attachment')
        );

        return response()->json($message);
    }

    public function show($conversationId)
    {
        $messages = $this->messageService->getMessages($conversationId);
        return response()->json($messages);
    }

}
