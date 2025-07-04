<?php

namespace App\Events;

use App\Models\MessageModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(MessageModel $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'message' => $this->message->message,
            'sender' => $this->message->sender,
            'sent_by_me' => false,
            'attachments' => $this->message->attachments->map(fn($a) => asset('storage/' . $a->file_path)),
            'created_at' => $this->message->created_at->diffForHumans(),
        ];
    }
}
