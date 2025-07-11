<?php

namespace App\Models;

use App\Models\RatingModel;
use App\Models\ListingImageModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListingModel extends Model
{
    use HasFactory;

    protected $table = "listings";
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'category',
        'price',
        'location',
        'feature_check',
        'img',
        'expiry_date',
        'dimensions',
        'sold',
    ];
    protected $appends = ['is_expire','vouch_count'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'expiry_date',
        'deleted_at',
    ];

    protected $casts = [
        'img' => 'array',
        'expired_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_expire' => 'datetime',
    ];


    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ListingImageModel::class, 'listing_id'); // explicitly set correct foreign key
    }

    public function ratings()
    {
        return $this->hasMany(RatingModel::class, 'listing_id');
    }


    public function getIsExpireAttribute()
    {
        if ($this->expired_at != null) {
            return 1;
        } else {
            return 0;
        }
    }


    public function getVouchCountAttribute()
    {
        return $this->hasMany(ListingVouch::class, 'listing_id')->count();
    }
}
