<?php

namespace App\Models;

use App\Models\MessageAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageModel extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'is_read',
    ];

    public function conversation()
    {
        return $this->belongsTo(ConversationModel::class, 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class, 'message_id');
    }

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
