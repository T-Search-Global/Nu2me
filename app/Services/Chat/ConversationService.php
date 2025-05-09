<?php

namespace App\Services\Chat;

use App\Models\ConversationModel;
use Illuminate\Http\Request;
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
    
    public function getUserConversations($userId)
    {
        return ConversationModel::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'listing'])
            ->latest()
            ->get();
    }



}
