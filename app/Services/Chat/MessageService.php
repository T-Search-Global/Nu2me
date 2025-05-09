<?php

namespace App\Services\Chat;

use App\Models\MessageModel;
use Illuminate\Http\Request;
use App\Models\ConversationModel;
use Illuminate\Support\Facades\Auth;

class MessageService
{

    public function storeMessage($conversationId, $senderId, $message)
    {
        return MessageModel::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'message' => $message
        ]);
    }

    public function getMessages($conversationId)
    {
        return MessageModel::where('conversation_id', $conversationId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }


}
