<?php

namespace App\Jobs;

use App\Models\ProductRanking;
use App\Models\Sustainability;
use App\Traits\SustainabilityRankingTrait;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateLevelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,SustainabilityRankingTrait;
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

        $rankings = ProductRanking::where('icon_calculated', 0)->get();

        foreach ($rankings as $key => $ranking) {
            $product_id = $ranking->product_id;

            $veified_icons = DB::table('product_sustainability')->where('product_id', $product_id)->where('is_verified', 1)->get();

            foreach ($veified_icons as $key => $veified_icon) {
                $icon = Sustainability::find($veified_icon->sustainability_id);

                if ($icon->sourcing) {
                    $ranking->sourcing_score = $ranking->sourcing_score - $ranking->sourcing_score * $icon->emisson_reduction;
                }
                if ($icon->manufacturing) {
                    $ranking->manufacturing_score = $ranking->manufacturing_score - $ranking->manufacturing_score * $icon->emisson_reduction;
                }
                if ($icon->packaging) {
                    $ranking->packaging_score = $ranking->packaging_score - $ranking->packaging_score * $icon->emisson_reduction;
                }
                if ($icon->shipping) {

                    $ranking->shipping_score = $ranking->shipping_score - $ranking->shipping_score * $icon->emisson_reduction;
                }
                if ($icon->use) {
                    $ranking->use_score = $ranking->emisson_reduction - $ranking->use_score * $icon->emisson_reduction;
                }
                if ($icon->end_of_life) {
                    $ranking->end_of_life_score = $ranking->end_of_life_score - $ranking->end_of_life_score * $icon->emisson_reduction;
                }
                $ranking->icon_calculated=1;
                $ranking->save();
            }
        }


        $this->calculateLevelAll();

    }
}
