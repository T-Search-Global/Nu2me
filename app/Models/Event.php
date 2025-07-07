<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = "events";

    protected $appends = ['url'];


    protected $fillable = ['name','date','description', 'image', 'approve', 'user_id', 'is_event_paid'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'image',
    ];
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
