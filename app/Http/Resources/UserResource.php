<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = null;
    public function toArray(Request $request): array
    {
        {
            return [
                'id'          => $this->id,
                'first_name'  => $this->first_name,
                'last_name'   => $this->last_name,
                'email'       => $this->email,
                'phone'       => $this->phone,
                'city'        => $this->city,
                'country'     => $this->country,
                'img'         => $this->img,
                'img_url'     => $this->img ? asset('storage/user/img/' . $this->img) : null,
                'listing_count'=> $this->listings()->count(),
                'is_paid' => $this->is_paid ? 1 : 0,
                'vouch_received_count' => $this->vouch_received_count,
            ];
        }
    }
}
