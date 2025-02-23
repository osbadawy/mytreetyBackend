<?php

namespace App\Jobs;

use App\Traits\ProductImportTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WoocomerceSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ProductImportTrait;
    private $products;
    private $user_id;
    private $woocommerce;
    public $timeout = 12000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products, $user_id, $woocommerce)
    {
        $this->products = $products;
        $this->user_id = $user_id;
        $this->woocommerce = $woocommerce;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->products as $key => $product) {

            $variations = null;

            if ($product->variations && count($product->variations) > 0) {
                $variations = $this->woocommerce->get("products/$product->id/variations");
            }

            $this->woocomerce_import($product, $this->user_id, $variations);
        }
    }
}
