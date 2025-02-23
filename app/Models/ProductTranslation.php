<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperProductTranslation
 */
class ProductTranslation extends Model
{
    protected $fillable = ['product_id', 'name', 'lang'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
