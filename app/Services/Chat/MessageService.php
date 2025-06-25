<?php

namespace App\Services\Chat;

use App\Events\MessageSent;
use App\Models\MessageModel;
use Illuminate\Http\Request;
use App\Models\ConversationModel;
use Illuminate\Support\Facades\Auth;

class MessageService
{

    public function storeMessage($conversationId, $senderId, $message)
    {
        $msg = MessageModel::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'message' => $message,
             'is_read' => false,
        ]);

        // Fire real-time event
        broadcast(new MessageSent($msg))->toOthers();

        return $msg;
    }

    // public function getMessages($conversationId)
    // {
    //     return MessageModel::where('conversation_id', $conversationId)
    //         ->with('sender')
    //         ->orderBy('created_at', 'asc')
    //         ->get();
    // }

    public function getMessages($conversationId)
{
    return MessageModel::where('conversation_id', $conversationId)
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender?->first_name . ' ' . $message->sender?->last_name,
                'sender_avatar' => $message->sender?->img
                    ? asset('storage/user/img/' . $message->sender->img)
                    : null,
                'message' => $message->message,
                'is_read' => (bool) $message->is_read,
                'created_at' => $message->created_at->toIso8601String(),
            ];
        });
}
    
}
