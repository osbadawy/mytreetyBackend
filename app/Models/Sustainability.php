<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSustainability
 */
class Sustainability extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'weight', 'lang', 'sustainability_id'];

    protected $with = ['sustainability_translations'];

    protected $hidden = [
        'sustainability_translations', 'slug'
    ];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = !$lang ? App::getLocale() : $lang;
        $sustainability_translation = $this->sustainability_translations->where('lang', $lang)->first();
        return $sustainability_translation != null ? $sustainability_translation->$field : $this->$field;
    }

    public function sustainability_translations(): HasMany
    {
        return $this->hasMany(SustainabilityTranslation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
