<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTax
 */
class Tax extends Model
{
    public function product_taxes(): HasMany
    {
        return $this->hasMany(ProductTax::class);
    }
}
