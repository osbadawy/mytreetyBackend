<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProductRanking
 */
class ProductRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sourcing_score','manufacturing_score','packaging_score','shipping_score','use_score','sourcing_level','manufacturing_level','packaging_level'
        ,'shipping_level','use_level','end_of_life_level','overall_sustainability_ranking','is_calculated'
    ];
}
