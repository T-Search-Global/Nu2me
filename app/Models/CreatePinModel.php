<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatePinModel extends Model
{
    use HasFactory;

    protected $table = 'pin';

    protected $fillable = [
        'name',
        'description',
        'category',
        'location',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
