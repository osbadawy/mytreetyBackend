<?php

namespace App\Jobs;

use App\Http\Controllers\Api\V2\ShopifySyncController;
use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use Log;

class ShopifyDailyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 12000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $sellers=Seller::where('verification_status',1)->where('shopify_accessToken','!=',null)->get();

       foreach ($sellers as $key => $seller) {

        (new ShopifySyncController)->SellerSync($seller->id);

       }


    }
}
