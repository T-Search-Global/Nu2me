<?php

namespace App\Console\Commands;

use App\Models\ListingModel;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetExpiredFeaturedListings extends Command
{
    protected $signature = 'app:reset-expired-featured-listings';
    protected $description = 'Reset featured listings that are expired';

    public function handle()
    {
        $expiredListings = ListingModel::
            whereDate('expiry_date', '<=', Carbon::today())
            ->get();

        foreach ($expiredListings as $listing) {
            $listing->feature_check = 0;
            $listing->expired_at = now();
            $listing->expiry_date = null;
            $listing->save(); // use save() to ensure all fields update
        }

        $this->info(count($expiredListings) . " listings reset successfully.");
    }
}
