<?php

namespace App\Jobs;

use App\Traits\ProductImportTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ShopifySync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels ,ProductImportTrait;
    private $products;
    private $user_id;
    public $timeout = 12000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products,$user_id)
    {
        $this->products = $products;
        $this->user_id= $user_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($this->products as $key => $product) {

            $this->import($product,$this->user_id);

        }

    }
}
