<?php

namespace App\Traits;

use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductRanking;
use App\Utility\CategoryUtility;
use DB;
use stdClass;

trait SustainabilityRankingTrait

{
    /**
     * @param $rankingDetails
     * @param $name
     * @param $user_id
     * @return int
     */
    public function createCollection($rankingDetails, $name, $user_id): int
    {
        $name ? $collection_name = $name : $collection_name = $this->generateRandomString();

        $ranking_details = $rankingDetails;
        $sourcing = $ranking_details['sourcing'];
        $manufacturing = $ranking_details['manufacturing'];
        $packaging = $ranking_details['packaging'];
        $shipping = $ranking_details['shipping'];
        $use = $ranking_details['use'];
        $endOfLife = $ranking_details['endOfLife'];


        $collection = new Collection;
        $collection->user_id = $user_id;

        //Saving ranking details
        $this->savingRankingDetails($collection_name, $sourcing, $collection, $manufacturing, $packaging, $shipping, $use['amount'], $endOfLife);

        return $collection->id;
    }

    function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * @param $collection_name
     * @param $sourcing
     * @param Collection $collection
     * @param $manufacturing
     * @param $packaging
     * @param $shipping
     * @param $amount
     * @param $endOfLife
     * @return void
     */
    public function savingRankingDetails($collection_name, $sourcing, Collection $collection, $manufacturing, $packaging, $shipping, $amount, $endOfLife): void
    {
        //saving collection name
        $collection->name = $collection_name;

        //saving sourcing details
        $collection->sourcing_transportation = $sourcing['transportation'];
        $collection->sourcing_transporationDistance = $sourcing['transporationDistance'];
        $collection->sourcing_exact = $sourcing['exactEmission'];

        //saving manufacturing details
        $collection->manufacturing_energyConsumed = $manufacturing['energyConsumed'];
        $collection->manufacturing_renewableFraction = $manufacturing['renewableFraction'];
        $collection->manufacturing_nonRenewableFraction = $manufacturing['nonRenewableFraction'];
        // $collection->manufacturing_icons = json_encode($manufacturing['icons']);

        //saving packaging details
        $collection->packaging_mass = 1;
        $collection->packaging_material = $packaging['material'];

        //saving shipping details
        $collection->shipping_transportation = $shipping['transportation'];
        $collection->shipping_distance = $shipping['transporationDistance'];
        $collection->shipping_exact = $shipping['exactEmission'];

        //saving use details
        $collection->use_amount = $amount;

        //saving endOfLife details
        $collection->endoflife_mass = $endOfLife['mass'];
        $collection->endoflife_recycledAmount = $endOfLife['recycledAmount'];
        $collection->endoflife_thrownAmount = $endOfLife['thrownAmount'];


        $collection->save();
    }

    public function attachCollection($product_id, $collection_id)
    {
        $product = Product::find($product_id);
        $product->collection_id = $collection_id;
        $product->published = 1;
        $product->approved = 1;
        $product->save();

        $category_id = 1;

        if (!$product->category || !$product->category->parentCategory->parentCategory) {

            return false;

        }

        $category_id = $product->category->parentCategory->parentCategory->id;

        $ranking = ProductRanking::firstOrCreate(['product_id' => $product_id]);
        $ranking->is_calculated = 0;
        $ranking->category_id = $category_id;
        $ranking->save();

        return true;
    }

    public function calculateScoreAll()
    {
        $rankings = ProductRanking::where('is_calculated', 0)->get();

        foreach ($rankings as $key => $ranking) {
            $this->calculateScore($ranking->id);
        }

        return true;
    }

    public function calculateScore($id)
    {
        $sourcing_score = 0;
        $manufacturing_score = 0;
        $packaging_score = 0;
        $shipping_score = 0;
        $use_score = 0;
        $end_of_life_score = 0;

        $product_ranking = ProductRanking::find($id);

        $product = Product::where('id', $product_ranking->product_id)->first();

        if (!$product || $product->collection_id == 0 || $product->collection_id == null) {
            return false;
        }


        $collection = Collection::where('id', $product->collection_id)->first();


        // calculate sourcing score

        if ($collection->sourcing_exact) {
            $sourcing_score = $collection->sourcing_exact;
        } else {
            $avg_emission = 0;
            if ($collection->sourcing_transportation == 'truck') {
                $avg_emission = 0.13;
            } elseif ($collection->sourcing_transportation == 'ship') {
                $avg_emission = 0.015;
            } elseif ($collection->sourcing_transportation == 'plane') {
                $avg_emission = 0.7;
            } elseif ($collection->sourcing_transportation == 'train') {
                $avg_emission = 0.03;
            }
            $sourcing_score = $collection->sourcing_transporationDistance * $avg_emission;
        }

        // save sourcing score to database
        $product_ranking->sourcing_score = $sourcing_score;


        // calculate manufacturing score


        $manufacturing_score = $collection->manufacturing_energyConsumed * (($collection->manufacturing_renewableFraction / 100) * 0.05 + ($collection->manufacturing_nonRenewableFraction / 100) * 0.6);

        // TODO icons here

        // save manufacturing score to database
        $product_ranking->manufacturing_score = $manufacturing_score;

        // calculate packaging score

        $emissionPerKg = 0;

        //TODO create table for material_emissions

        $material_emissions = new stdClass();
        $material_emissions->GeneralPlastic = 3;
        $material_emissions->RecycledGeneralPlastic = 2;
        $material_emissions->LPDEorPETPlastic = 3.4;
        $material_emissions->RecycledLPDEorPETPlastic = 1.7;
        $material_emissions->PPPlastic = 2.7;
        $material_emissions->RecycledPPPlastic = 1.6;
        $material_emissions->PSPlastic = 3.3;
        $material_emissions->RecycledPSPlastic = 1.7;
        $material_emissions->Paper = 1.3;
        $material_emissions->RecycledPaper = 0.8;
        $material_emissions->CardboardBoxes = 1.5;
        $material_emissions->CorrugatedCardboard = 0.95;
        $material_emissions->RecycledCardboard = 1.1;
        $material_emissions->RecycledCorrugatedCardboard = 0.7;
        $material_emissions->Aluminium = 6;
        $material_emissions->RecycledAluminium = 2;
        $material_emissions->Glass = 4.5;
        $material_emissions->RecycledGlass = 1.4;

        $material = preg_replace('/\s+/', '', $collection->packaging_material);

        if (property_exists($material_emissions, $material)) {
            $emissionPerKg = $material_emissions->$material;
        }

        $packaging_score = $collection->packaging_mass * $emissionPerKg;

        // save packaging score to database
        $product_ranking->packaging_score = $packaging_score;


        // calculate shipping score

        if ($collection->shipping_exact) {
            $shipping_score = $collection->shipping_exact;
        } else {
            $avg_emission = 0;
            if ($collection->shipping_transportation == 'truck') {
                $avg_emission = 0.13;
            } elseif ($collection->shipping_transportation == 'ship') {
                $avg_emission = 0.015;
            } elseif ($collection->shipping_transportation == 'plane') {
                $avg_emission = 0.7;
            } elseif ($collection->shipping_transportation == 'train') {
                $avg_emission = 0.03;
            }
            $shipping_score = $collection->shipping_distance * $avg_emission;
        }
        // check if green shipping
        if ($collection->is_green) {
            $shipping_score = $shipping_score * 0.8;
        }

        // save shipping score to database
        $product_ranking->shipping_score = $shipping_score;


        // calculate use score

        $use_score = $collection->use_amount * 0.19;

        // save use score to database
        $product_ranking->use_score = $use_score;


        // calculate end of life score

        $end_of_life_score = $collection->endoflife_mass * ($collection->endoflife_thrownAmount / 100) * 0.65;

        // save use score to database
        $product_ranking->end_of_life_score = $end_of_life_score;

        // save all
        $product_ranking->is_calculated = 1;
        $product_ranking->save();

        return true;
    }

    public function calculateLevelAll(): bool
    {
        $rankings = ProductRanking::all();
        $rankings_count = $rankings->count();
        $count_25_percent = intval(0.25 * $rankings_count);

        //men,woman,pets,home&living,kids
        $category_ids = [1, 2, 3, 4, 63,98];
        foreach ($category_ids as $key => $category_id) {

            //Add sub cats with the parent
            if (!empty($category_id)) {
                $n_cid = [];

                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($category_id));

                if (!empty($n_cid)) {
                    $cat_ids = array_merge($category_ids, $n_cid);
                }
            }

            $category_ranking = $rankings->whereIn('category_id', $cat_ids);

            // calculate new averages

            $this->CalculateAvgAll($category_ranking, $rankings_count, $category_id);

            // calculate sourcing_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'sourcing_score', 'sourcing_level','sourcing_rankings',$category_id);

            // calculate manufacturing_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'manufacturing_score', 'manufacturing_level','manufacturing_rankings',$category_id);

            // calculate packaging_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'packaging_score', 'packaging_level','packaging_rankings',$category_id);

            // calculate shipping_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'shipping_score', 'shipping_level','shipping_rankings',$category_id);

            // calculate use_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'use_score', 'use_level','use_rankings',$category_id);

            // calculate end_of_life_level
            $this->CalculateLevel($category_ranking, $count_25_percent, 'end_of_life_score', 'end_of_life_level','end_of_life_rankings',$category_id);

            // calculate overall ranking
            $this->CalculateOverallAll($category_ranking);
        }


        return true;
    }

    public function CalculateAvgAll($rankings, $rankings_count, $category_id)
    {
        $sourcing_rankings = [];
        $manufacturing_rankings = [];
        $packaging_rankings = [];
        $shipping_rankings = [];
        $use_rankings = [];
        $end_of_life_rankings = [];


        // put the score values into array
        foreach ($rankings as $key => $rank) {

            $sourcing_rankings[] = $rank->sourcing_score;
            $manufacturing_rankings[] = $rank->manufacturing_score;
            $packaging_rankings[] = $rank->packaging_score;
            $shipping_rankings[] = $rank->shipping_score;
            $use_rankings[] = $rank->use_score;
            $end_of_life_rankings[] = $rank->end_of_life_score;
        };


        //get deviation for each partition each category
        // $sourcing_deviation = round($this->Stand_Deviation($sourcing_rankings), 3);
        $manufacturing_deviation = round($this->Stand_Deviation($manufacturing_rankings), 3);
        // $shipping_deviation = round($this->Stand_Deviation($shipping_rankings), 3);
        $packaging_deviation = round($this->Stand_Deviation($packaging_rankings), 3);
        $use_deviation = round($this->Stand_Deviation($use_rankings), 3);
        $end_of_life_deviation = round($this->Stand_Deviation($end_of_life_rankings), 3);


        //calculate sourcing Avg
        // $this->CalculateAvg('sourcing_rankings', $sourcing_deviation, $category_id, $rankings_count, $sourcing_rankings);

        //calculate manufacturing Avg
        $this->CalculateAvg('manufacturing_rankings', $manufacturing_deviation, $category_id, $rankings_count, $manufacturing_rankings);

        //calculate packaging Avg
        $this->CalculateAvg('packaging_rankings', $packaging_deviation, $category_id, $rankings_count, $packaging_rankings);

        //calculate shipping Avg
        // $this->CalculateAvg('shipping_rankings', $shipping_deviation, $category_id, $rankings_count, $shipping_rankings);

        //calculate use Avg
        $this->CalculateAvg('use_rankings', $use_deviation, $category_id, $rankings_count, $use_rankings);

        //calculate end_of_life Avg
        $this->CalculateAvg('end_of_life_rankings', $end_of_life_deviation, $category_id, $rankings_count, $end_of_life_rankings);
    }

    function Stand_Deviation($arr)
    {

        $num_of_elements = count($arr);

        $variance = 0.0;

        if ($num_of_elements > 0) {

            // calculating mean using array_sum() method
            $average = array_sum($arr) / $num_of_elements;

            foreach ($arr as $i) {
                // sum of squares of differences between
                // all numbers and means.
                $variance += pow(($i - $average), 2);
            }

            $sd = (float)sqrt($variance / $num_of_elements);
        } else {
            $sd = 0;
        }

        return $sd;

    }



    // function to calculate the standard deviation
    // of array elements

    public function CalculateAvg($type, $deviation, $category_id, $rankings_count, $sourcing_rankings): bool
    {
        if ($rankings_count > 0) {

            $t_50 = round(array_sum($sourcing_rankings) / $rankings_count, 3);

            $t_25 = $t_50 + (-0.67 * $deviation);
            $t_75 = $t_50 + (0.68 * $deviation);


            DB::table($type)->updateOrInsert(
                ['category_id' => (int)$category_id],
                ['t_25' => $t_25, 't_50' => $t_50, 't_75' => $t_75, 'updated_at' => now()]
            );
        }


        return true;
    }

    public function CalculateLevel($rankings, $count_25_percent, $score_type, $level_type,$avg_table,$category_id): bool
    {
        $avarge=DB::table($avg_table)->where('category_id',$category_id)->first();
        foreach ($rankings as $key => $ranking) {
            $rank=1;

            if($ranking->$score_type < $avarge->t_25){
                $rank = 4;
            }
            elseif($ranking->$score_type > $avarge->t_25 && $ranking->$score_type < $avarge->t_50){
                $rank = 3;
           }
           elseif($ranking->$score_type > $avarge->t_50 && $ranking->$score_type < $avarge->t_75){
                $rank = 2;
           }

            $ranking->$level_type= $rank;
            $ranking->save();

        }



        return true;
    }

    public function CalculateOverallAll($rankings): bool
    {
        foreach ($rankings as $key => $ranking) {

            $overall_sustainability_ranking = ($ranking->sourcing_level + $ranking->manufacturing_level + $ranking->packaging_level + $ranking->shipping_level + $ranking->use_level + $ranking->end_of_life_level) / 6;
            $ranking->overall_sustainability_ranking = $overall_sustainability_ranking;
            $ranking->save();

            $product = Product::find($ranking->product_id);
            if($product){
                $product->sustainability_rank = $overall_sustainability_ranking;
                $product->update();
            }else{
                $ranking->delete();
            }
        }
        return true;
    }
}
