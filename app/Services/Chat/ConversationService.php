<?php

namespace App\Services\Chat;

use App\Models\MessageModel;
use Illuminate\Http\Request;
use App\Models\ConversationModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ConversationService
{

    public function createOrGetConversation($senderId, $receiverId, $listingId)
    {
        return ConversationModel::firstOrCreate([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'listing_id' => $listingId,
        ]);
    }


    public function getUserConversations($authUserId)
    {
        $conversations = ConversationModel::with([
            'sender:id,first_name',
            'receiver:id,first_name,last_name,img',
            'latestMessage:id,messages.conversation_id,message,sender_id,created_at'
        ])
            ->where('sender_id', $authUserId)
            ->orWhere('receiver_id', $authUserId)
            ->get()
            ->map(function ($conversation) {
                   $createdAt = optional($conversation->latestMessage?->created_at);
                return [
                    'conversationId' => $conversation->id,
                    'sender_id' => $conversation->sender_id,
                    'receiver_id' => $conversation->receiver_id,
                    'senderName' => $conversation->sender?->first_name,
                    'receiverName' => $conversation->receiver?->first_name . ' ' . $conversation->receiver?->last_name,
                    'receiver_avatar' => $conversation->receiver?->img_url,
                    'lastMessage' => $conversation->latestMessage?->message,
                    'messageTime' => $createdAt ? $createdAt->diffForHumans() : null,
                ];
            });

        return $conversations;
    }
}
