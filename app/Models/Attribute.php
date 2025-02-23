<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperAttribute
 */
class Attribute extends Model
{


    protected $fillable = ['name'];

    protected $with = ['attribute_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = !$lang ? App::getLocale() : $lang;
        $attribute_translation = $this->attribute_translations->where('lang', $lang)->first();
        return $attribute_translation != null ? $attribute_translation->$field : $this->$field;
    }

    public function attribute_translations(): HasMany
    {
        return $this->hasMany(AttributeTranslation::class);
    }

    public function attribute_values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

}
