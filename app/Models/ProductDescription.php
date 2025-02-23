<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperProductDescription
 */
class ProductDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'product_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTranslation($field = '', $lang = false)
    {
        $lang = !$lang ? App::getLocale() : $lang;
        $product_description_translations = $this->product_description_translations->where('lang', $lang)->first();
        return $product_description_translations != null ? $product_description_translations->$field : $this->$field;
    }

    public function product_description_translations(): HasMany
    {
        return $this->hasMany(ProductDescriptionTranslations::class,'product_description_id');
    }
}
