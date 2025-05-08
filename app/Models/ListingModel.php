<?php

namespace App\Models;

use App\Models\ListingImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'img' => 'array',
    ];


    public function images()
    {
        return $this->hasMany(ListingImageModel::class, 'listing_id'); // explicitly set correct foreign key
    }


}
