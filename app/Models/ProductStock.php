<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperProductStock
 */
class ProductStock extends Model
{
    protected $fillable = ['product_id', 'qty', 'price','color','variant','value','title'];

    public function product(): BelongsTo
    {
    	return $this->belongsTo(Product::class);
    }

}
