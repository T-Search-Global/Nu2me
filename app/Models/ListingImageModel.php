<?php

namespace App\Models;

use App\Models\ListingModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingImageModel extends Model
{
    use HasFactory;

    protected $table = "listing_images";

    protected $fillable = ['listing_id', 'image_path'];

    public function listing()
    {
        return $this->belongsTo(ListingModel::class, 'listing_id');
    }


    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

}
