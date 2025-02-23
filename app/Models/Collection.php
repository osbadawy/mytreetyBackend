<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCollection
 */
class Collection extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sourcing_transportation', 'sourcing_transporationDistance', 'sourcing_distance', 'sourcing_exact', 'manufacturing_energyConsumed', 'manufacturing_renewableFraction', 'manufacturing_nonRenewableFraction', 'manufacturing_icons', 'packaging_mass', 'packaging_material', 'shipping_transportation', 'shipping_distance', 'shipping_exact', 'use_amount', 'endoflife_mass', 'endoflife_recycledAmount', 'endoflife_thrownAmount', 'is_green'
    ];
}
