<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingModel extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'rating',
        'description',
        'user_id',
    ];

    public $timestamps = false;

    protected $casts = [
        'rating' => 'integer',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
