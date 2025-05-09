<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationModel extends Model
{
    use HasFactory;

    protected $table = 'conversations';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'listing_id',
    ];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }
    
    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function listing(){
        return $this->belongsTo(ListingModel::class, 'listing_id');
    }
}
