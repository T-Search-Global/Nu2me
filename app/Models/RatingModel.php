<?php

namespace App\Models;

use App\Models\ListingModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RatingModel extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'rating',
        'description',
        'user_id',
        'listing_id',
    ];

    public $timestamps = false;

    protected $casts = [
        'rating' => 'integer',
    ];


      protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
        'listing_id',
    ];
   public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(ListingModel::class);
    }
}
