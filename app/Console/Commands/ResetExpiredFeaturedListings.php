<?php

namespace App\Console\Commands;

use App\Models\ListingModel;
use Illuminate\Console\Command;

class ResetExpiredFeaturedListings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-expired-featured-listings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
{
    $expiredListings = ListingModel::where('feature_check', 1)
        ->whereDate('expiry_date', '<=', now())
        ->get();

    foreach ($expiredListings as $listing) {
        $listing->update([
            'feature_check' => 0,
            'expiry_date' => null,
        ]);
    }

    $this->info(count($expiredListings) . " listings reset.");
}

}
