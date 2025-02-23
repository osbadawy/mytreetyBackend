<?php

namespace App\Traits;

use App\Models\PointsHistory;
use App\Models\PointsUsage;
use App\Models\User;

trait PointsTrait

{

    public function addPoints($user_id, $points, $reason, $order_id)
    {
        $point_history = new PointsHistory;
        $point_history->user_id = $user_id;
        $point_history->points = $points;
        $point_history->reason = $reason;
        $point_history->order_id = $order_id;
        $point_history->save();

        $user = User::find($user_id);
        $user->points = $user->points + $points;
        $user->save();

        return true;
    }

    public function subPoints($user_id, $points, $reason, $order_id)
    {
        $user = User::find($user_id);
        $user->points = $user->points - $points;
        $user->save();

        $point_usage = new PointsUsage;
        $point_usage->user_id = $user_id;
        $point_usage->points = $points;
        $point_usage->reason = $reason;
        $point_usage->order_id = $order_id;
        $point_usage->save();

        return true;
    }

    public function checkIfPointsAvailable($user_id, $points)
    {
        $user = User::find($user_id);
        if ($points > $user->points) {
            return false;
        }

        return true;
    }

    public function calculateOrderPoints($order_price)
    {
        return ($order_price * 0.09) * 100;
    }

    public function GetMaxPointsPercentage($points, $order_price)
    {

        $discount=$points/100;
        if($discount > $order_price){
            $points=$order_price * 100;
        }

        return floor($points / $order_price);
    }
}
