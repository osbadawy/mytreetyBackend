<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'added_by', 'user_id', 'category_id', 'video_provider', 'video_link', 'unit_price',
         'unit', 'slug', 'approved', 'choice_options', 'thumbnail_img',
        'manufactured','distributed','est_shipping_days','shipping_cost','published','photos','collection_id','product_type'
    ];

    protected $with = ['product_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = !$lang ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function product_descriptions(): HasMany
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function sustainabilities(): BelongsToMany
    {

        return $this->belongsToMany(Sustainability::class, 'product_sustainability')->withPivot('is_verified');

    }
}
