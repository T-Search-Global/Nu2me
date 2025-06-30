<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatePinModel extends Model
{
    use HasFactory;

    protected $table = 'pins';

    protected $fillable = [
        'name',
        'description',
        'category',
        'location',
        'user_id',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
