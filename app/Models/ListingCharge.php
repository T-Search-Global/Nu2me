<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingCharge extends Model
{
    use HasFactory;

    protected $table = "listing_charges";
     protected $fillable = [
        'feature_listing_amount',
        'additional_listing_amount',
    ];
}
