<?php

namespace App\Services\Chat;

use App\Events\MessageSent;
use App\Models\MessageModel;
use Illuminate\Http\Request;
use App\Models\ConversationModel;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Auth;

class MessageService
{

    public function storeMessage($conversationId, $senderId, $message, $attachment)
    {
        $msg = MessageModel::create([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'message' => $message,
            'is_read' => false,
        ]);

        if (!empty($attachment)) {
            foreach ($attachment as $file) {
                $path = $file->store('chat/attachments', 'public');
                MessageAttachment::create([
                    'message_id' => $msg->id,
                    'file_path' => $path,
                ]);
            }
        }



        // Fire real-time event
        broadcast(new MessageSent($msg->load('attachments')))->toOthers();

        return [
            'id' => $msg->id,
            'conversation_id' => $msg->conversation_id,
            'sender_id' => $msg->sender_id,
            'message' => $msg->message,
            'sent_by_me' => true,
            'attachments' => $msg->attachments->map(fn($a) => asset('storage/' . $a->file_path)),
            'created_at' => $msg->created_at->diffForHumans(),
        ];
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
        $currentUserId = auth()->id();

        $messages = MessageModel::where('conversation_id', $conversationId)
            ->with(['sender:id,first_name,last_name,img', 'attachments']) // Eager load attachments
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($currentUserId) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => trim($message->sender?->first_name . ' ' . $message->sender?->last_name),
                    'sender_avatar' => $message->sender?->img
                        ? asset('storage/user/img/' . $message->sender->img)
                        : null,
                    'receiver_id' => $message->sender_id === $currentUserId
                        ? $message->conversation->receiver_id
                        : $message->conversation->sender_id,
                    'message' => $message->message,
                    'attachments' => $message->attachments->map(function ($attachment) {
                        return asset('storage/' . $attachment->file_path);
                    }),
                    'is_read' => (bool) $message->is_read,
                    'sent_by_me' => $message->sender_id === $currentUserId,
                    'created_at' => $message->created_at->diffForHumans(),
                ];
            });

        return [
            'conversation_id' => $conversationId,
            'current_user_id' => $currentUserId,
            'messages' => $messages
        ];
    }
}
