<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;
    protected $table = "payments";
    protected $fillable = [
        'user_id',
        'listing_id',
        'amount',
        'payment_type',
        'payment_status',
        'payment_gateway',
        'transaction_id',
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
