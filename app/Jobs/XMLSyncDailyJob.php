<?php

namespace App\Jobs;

use App\Http\Controllers\Api\V2\ProductBulkUploadController;
use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class XMLSyncDailyJob implements ShouldQueue
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
        $sellers = Seller::where('verification_status', 1)->where('xml_file', '!=', null)->get();

        foreach ($sellers as $key => $seller) {

            (new ProductBulkUploadController())->SellerSync($seller->id);

        }
    }
}
